<?php

declare(strict_types=1);

namespace adimiuprix\CoinMarketCap\Tests\Unit;

use GuzzleHttp\Psr7\HttpFactory;
use GuzzleHttp\Psr7\Response;
use PHPUnit\Framework\TestCase;
use Psr\Http\Client\ClientInterface;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;
use adimiuprix\CoinMarketCap\Client;
use adimiuprix\CoinMarketCap\Exception\ApiException;
use adimiuprix\CoinMarketCap\Exception\AuthenticationException;
use adimiuprix\CoinMarketCap\Exception\InvalidRequestException;
use adimiuprix\CoinMarketCap\Exception\NotFoundException;
use adimiuprix\CoinMarketCap\Exception\RateLimitException;
use adimiuprix\CoinMarketCap\Http\HttpClient;

class HttpClientTest extends TestCase
{
    public function test_get_sends_an_authenticated_request_and_decodes_the_response(): void
    {
        $transport = new RecordingHttpClient(
            new Response(200, ['Content-Type' => 'application/json'], '{"data":{"id":1}}')
        );

        $data = $this->httpClient($transport)->get('/v1/test', [
            'convert' => 'USD,EUR',
            'value' => 'a value&more',
        ]);

        $request = $transport->requests[0];
        parse_str($request->getUri()->getQuery(), $query);

        self::assertSame(['data' => ['id' => 1]], $data);
        self::assertSame('/v1/test', $request->getUri()->getPath());
        self::assertSame(['convert' => 'USD,EUR', 'value' => 'a value&more'], $query);
        self::assertSame('test-key', $request->getHeaderLine('X-CMC_PRO_API_KEY'));
        self::assertSame('application/json', $request->getHeaderLine('Accept'));
    }

    public function test_post_sends_a_json_body(): void
    {
        $transport = new RecordingHttpClient(
            new Response(200, ['Content-Type' => 'application/json'], '{"data":"ok"}')
        );

        $data = $this->httpClient($transport)->post('v1/test', ['id' => 1]);

        $request = $transport->requests[0];

        self::assertSame(['data' => 'ok'], $data);
        self::assertSame('POST', $request->getMethod());
        self::assertSame('/v1/test', $request->getUri()->getPath());
        self::assertSame('application/json', $request->getHeaderLine('Content-Type'));
        self::assertSame('{"id":1}', (string) $request->getBody());
    }

    public function test_all_services_use_the_documented_endpoint_paths(): void
    {
        $transport = new RecordingHttpClient(
            ...array_fill(0, 33, new Response(200, ['Content-Type' => 'application/json'], '{"data":{}}'))
        );
        $client = new Client($this->httpClient($transport));

        $calls = [
            ['/v3/cryptocurrency/listings/latest', static fn (): array => $client->cryptocurrency()->listingsLatest()],
            ['/v1/cryptocurrency/listings/historical', static fn (): array => $client->cryptocurrency()->listingsHistorical()],
            ['/v1/cryptocurrency/listings/new', static fn (): array => $client->cryptocurrency()->listingsNew()],
            ['/v3/cryptocurrency/quotes/latest', static fn (): array => $client->cryptocurrency()->quotesLatest()],
            ['/v3/cryptocurrency/quotes/historical', static fn (): array => $client->cryptocurrency()->quotesHistorical()],
            ['/v2/cryptocurrency/info', static fn (): array => $client->cryptocurrency()->info()],
            ['/v1/cryptocurrency/map', static fn (): array => $client->cryptocurrency()->map()],
            ['/v2/cryptocurrency/ohlcv/latest', static fn (): array => $client->cryptocurrency()->ohlcvLatest()],
            ['/v2/cryptocurrency/ohlcv/historical', static fn (): array => $client->cryptocurrency()->ohlcvHistorical()],
            ['/v1/cryptocurrency/categories', static fn (): array => $client->cryptocurrency()->categories()],
            ['/v1/cryptocurrency/category', static fn (): array => $client->cryptocurrency()->category()],
            ['/v2/cryptocurrency/market-pairs/latest', static fn (): array => $client->cryptocurrency()->marketPairsLatest()],
            ['/v2/cryptocurrency/price-performance-stats/latest', static fn (): array => $client->cryptocurrency()->pricePerformanceStatsLatest()],
            ['/v1/simple/price', static fn (): array => $client->cryptocurrency()->simplePrice()],
            ['/v1/cryptocurrency/airdrops', static fn (): array => $client->cryptocurrency()->airdrops()],
            ['/v1/cryptocurrency/airdrop', static fn (): array => $client->cryptocurrency()->airdrop()],
            ['/v1/cryptocurrency/trending/latest', static fn (): array => $client->cryptocurrency()->trendingLatest()],
            ['/v1/cryptocurrency/trending/gainers-losers', static fn (): array => $client->cryptocurrency()->trendingGainersLosers()],
            ['/v1/cryptocurrency/trending/most-visited', static fn (): array => $client->cryptocurrency()->trendingMostVisited()],
            ['/v1/exchange/listings/latest', static fn (): array => $client->exchange()->listingsLatest()],
            ['/v1/exchange/quotes/latest', static fn (): array => $client->exchange()->quotesLatest()],
            ['/v1/exchange/quotes/historical', static fn (): array => $client->exchange()->quotesHistorical()],
            ['/v1/exchange/info', static fn (): array => $client->exchange()->info()],
            ['/v1/exchange/map', static fn (): array => $client->exchange()->map()],
            ['/v1/exchange/market-pairs/latest', static fn (): array => $client->exchange()->marketPairsLatest()],
            ['/v1/exchange/assets', static fn (): array => $client->exchange()->assets()],
            ['/v1/global-metrics/quotes/latest', static fn (): array => $client->globalMetrics()->quotesLatest()],
            ['/v1/global-metrics/quotes/historical', static fn (): array => $client->globalMetrics()->quotesHistorical()],
            ['/v3/fear-and-greed/latest', static fn (): array => $client->globalMetrics()->fearAndGreedLatest()],
            ['/v3/fear-and-greed/historical', static fn (): array => $client->globalMetrics()->fearAndGreedHistorical()],
            ['/v1/altcoin-season-index/latest', static fn (): array => $client->globalMetrics()->altcoinSeasonIndexLatest()],
            ['/v1/altcoin-season-index/historical', static fn (): array => $client->globalMetrics()->altcoinSeasonIndexHistorical()],
            ['/v2/tools/price-conversion', static fn (): array => $client->tools()->priceConversion()],
        ];

        foreach ($calls as $index => [$path, $call]) {
            $call();

            self::assertSame($path, $transport->requests[$index]->getUri()->getPath());
        }
    }

    public function test_maps_api_errors_to_their_specific_exceptions(): void
    {
        $cases = [
            [400, InvalidRequestException::class],
            [401, AuthenticationException::class],
            [404, NotFoundException::class],
            [429, RateLimitException::class],
        ];

        foreach ($cases as [$statusCode, $exceptionClass]) {
            $transport = new RecordingHttpClient(new Response(
                $statusCode,
                ['Content-Type' => 'application/json'],
                '{"status":{"error_message":"Request failed","retry_after":15}}'
            ));

            try {
                $this->httpClient($transport)->get('/test');
                self::fail('Expected an API exception.');
            } catch (ApiException $exception) {
                self::assertInstanceOf($exceptionClass, $exception);
                self::assertSame($statusCode, $exception->statusCode);
                self::assertSame('Request failed', $exception->getMessage());

                if ($exception instanceof RateLimitException) {
                    self::assertSame(15, $exception->getRetryAfter());
                }
            }
        }
    }

    public function test_rate_limit_uses_retry_after_header_when_not_present_in_the_body(): void
    {
        $transport = new RecordingHttpClient(new Response(
            429,
            ['Content-Type' => 'application/json', 'Retry-After' => '30'],
            '{"status":{"error_message":"Too many requests"}}'
        ));

        try {
            $this->httpClient($transport)->get('/test');
            self::fail('Expected a rate limit exception.');
        } catch (RateLimitException $exception) {
            self::assertSame(30, $exception->getRetryAfter());
        }
    }

    public function test_invalid_json_in_a_successful_response_is_an_api_exception(): void
    {
        $transport = new RecordingHttpClient(new Response(200, [], 'not JSON'));

        try {
            $this->httpClient($transport)->get('/test');
            self::fail('Expected an API exception.');
        } catch (ApiException $exception) {
            self::assertSame(200, $exception->statusCode);
            self::assertStringContainsString('Failed to parse JSON response', $exception->getMessage());
            self::assertNull($exception->getResponse());
        }
    }

    public function test_non_json_error_response_preserves_the_http_status(): void
    {
        $transport = new RecordingHttpClient(new Response(502, [], 'upstream unavailable'));

        try {
            $this->httpClient($transport)->get('/test');
            self::fail('Expected an API exception.');
        } catch (ApiException $exception) {
            self::assertSame(502, $exception->statusCode);
            self::assertSame('Bad Gateway', $exception->getMessage());
            self::assertNull($exception->getResponse());
        }
    }

    private function httpClient(ClientInterface $transport): HttpClient
    {
        $factory = new HttpFactory();

        return new HttpClient($transport, $factory, $factory, 'https://api.example.test/', 'test-key');
    }
}

class RecordingHttpClient implements ClientInterface
{
    /** @var array<int, RequestInterface> */
    public array $requests = [];

    /** @var array<int, ResponseInterface> */
    private array $responses;

    public function __construct(ResponseInterface ...$responses)
    {
        $this->responses = $responses;
    }

    public function sendRequest(RequestInterface $request): ResponseInterface
    {
        $this->requests[] = $request;

        if ($this->responses === []) {
            throw new \LogicException('No response was configured for this request.');
        }

        return array_shift($this->responses);
    }
}
