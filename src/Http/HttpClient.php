<?php

declare(strict_types=1);

namespace adimiuprix\CoinMarketCap\Http;

use Psr\Http\Client\ClientInterface;
use Psr\Http\Message\RequestFactoryInterface;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\StreamFactoryInterface;
use adimiuprix\CoinMarketCap\Exception\ApiException;
use adimiuprix\CoinMarketCap\Exception\AuthenticationException;
use adimiuprix\CoinMarketCap\Exception\InvalidRequestException;
use adimiuprix\CoinMarketCap\Exception\NotFoundException;
use adimiuprix\CoinMarketCap\Exception\RateLimitException;

class HttpClient
{
    public function __construct(
        private readonly ClientInterface $httpClient,
        private readonly RequestFactoryInterface $requestFactory,
        private readonly StreamFactoryInterface $streamFactory,
        private readonly string $baseUrl,
        private readonly string $apiKey
    ) {
    }

    /**
     * @param array<string, mixed> $params
     * @return array<string, mixed>
     */
    public function get(string $endpoint, array $params = []): array
    {
        $url = $this->buildUrl($endpoint, $params);
        $request = $this->requestFactory->createRequest('GET', $url)
            ->withHeader('X-CMC_PRO_API_KEY', $this->apiKey)
            ->withHeader('Accept', 'application/json');

        return $this->send($request);
    }

    /**
     * @param array<string, mixed> $data
     * @return array<string, mixed>
     */
    public function post(string $endpoint, array $data = []): array
    {
        $url = $this->buildUrl($endpoint, []);

        try {
            $body = $this->streamFactory->createStream(json_encode($data, JSON_THROW_ON_ERROR));
        } catch (\JsonException $e) {
            throw new ApiException('Failed to encode JSON request body: ' . $e->getMessage(), 0, null, $e);
        }

        $request = $this->requestFactory->createRequest('POST', $url)
            ->withHeader('X-CMC_PRO_API_KEY', $this->apiKey)
            ->withHeader('Accept', 'application/json')
            ->withHeader('Content-Type', 'application/json')
            ->withBody($body);

        return $this->send($request);
    }

    /**
     * @param array<string, mixed> $params
     */
    private function buildUrl(string $endpoint, array $params): string
    {
        $url = rtrim($this->baseUrl, '/') . '/' . ltrim($endpoint, '/');

        if (! empty($params)) {
            $url .= '?' . http_build_query($params);
        }

        return $url;
    }

    /** @return array<string, mixed> */
    private function send(RequestInterface $request): array
    {
        try {
            $response = $this->httpClient->sendRequest($request);
            $statusCode = $response->getStatusCode();
            $body = (string) $response->getBody();

            if ($statusCode >= 400) {
                $this->handleError(
                    $statusCode,
                    $this->decodeErrorResponse($body, $statusCode, $response->getReasonPhrase()),
                    $response->getHeaderLine('Retry-After')
                );
            }

            return $this->decodeResponse($body, $statusCode);
        } catch (ApiException $e) {
            throw $e;
        } catch (\Throwable $e) {
            throw new ApiException('HTTP request failed: ' . $e->getMessage(), 0, null, $e);
        }
    }

    /** @return array<string, mixed> */
    private function decodeResponse(string $body, int $statusCode): array
    {
        try {
            $data = json_decode($body, true, 512, JSON_THROW_ON_ERROR);
        } catch (\JsonException $e) {
            throw new ApiException('Failed to parse JSON response: ' . $e->getMessage(), $statusCode, null, $e);
        }

        if (! is_array($data)) {
            throw new ApiException('Unexpected JSON response type; expected an object or array', $statusCode);
        }

        return $data;
    }

    /** @return array<string, mixed> */
    private function decodeErrorResponse(string $body, int $statusCode, string $reasonPhrase): array
    {
        try {
            return $this->decodeResponse($body, $statusCode);
        } catch (ApiException $e) {
            $message = $reasonPhrase !== '' ? $reasonPhrase : 'HTTP error response is not valid JSON';

            throw new ApiException($message, $statusCode, null, $e);
        }
    }

    /** @param array<string, mixed> $response */
    private function handleError(int $statusCode, array $response, string $retryAfterHeader): void
    {
        $status = $this->status($response);
        $message = $status['error_message'] ?? 'Unknown error';
        $message = is_string($message) ? $message : 'Unknown error';

        match ($statusCode) {
            400 => throw new InvalidRequestException($message, $response),
            401 => throw new AuthenticationException($message, $response),
            404 => throw new NotFoundException($message, $response),
            429 => throw new RateLimitException(
                $message,
                $this->retryAfter($status, $retryAfterHeader),
                $response
            ),
            default => throw new ApiException($message, $statusCode, $response),
        };
    }

    /**
     * @param array<string, mixed> $status
     */
    private function retryAfter(array $status, string $retryAfterHeader): ?int
    {
        $retryAfter = $status['retry_after'] ?? null;

        if (is_int($retryAfter) && $retryAfter >= 0) {
            return $retryAfter;
        }

        if (is_float($retryAfter) && $retryAfter >= 0) {
            return (int) $retryAfter;
        }

        if (is_string($retryAfter) && ctype_digit($retryAfter)) {
            return (int) $retryAfter;
        }

        if (ctype_digit($retryAfterHeader)) {
            return (int) $retryAfterHeader;
        }

        $retryAt = strtotime($retryAfterHeader);

        return $retryAt === false ? null : max(0, $retryAt - time());
    }

    /**
     * @param array<string, mixed> $response
     * @return array<string, mixed>
     */
    private function status(array $response): array
    {
        $status = $response['status'] ?? [];

        return is_array($status) ? $status : [];
    }
}
