<?php

declare(strict_types=1);

namespace adimiuprix\CoinMarketCap;

use GuzzleHttp\Client as GuzzleClient;
use GuzzleHttp\Psr7\HttpFactory;
use Psr\Http\Client\ClientInterface;
use Psr\Http\Message\RequestFactoryInterface;
use Psr\Http\Message\StreamFactoryInterface;
use adimiuprix\CoinMarketCap\Http\HttpClient;

class ClientBuilder
{
    private ?string $apiKey = null;
    private string $baseUrl = 'https://pro-api.coinmarketcap.com';
    private int $timeout = 30;
    private ?ClientInterface $httpClient = null;
    private ?RequestFactoryInterface $requestFactory = null;
    private ?StreamFactoryInterface $streamFactory = null;

    public function setApiKey(string $apiKey): self
    {
        $this->apiKey = $apiKey;

        return $this;
    }

    public function setBaseUrl(string $baseUrl): self
    {
        $baseUrl = rtrim($baseUrl, '/');
        $scheme = parse_url($baseUrl, PHP_URL_SCHEME);

        if (
            $baseUrl === ''
            || filter_var($baseUrl, FILTER_VALIDATE_URL) === false
            || ! in_array(strtolower((string) $scheme), ['http', 'https'], true)
        ) {
            throw new \InvalidArgumentException('Base URL must be a valid HTTP(S) URL');
        }

        $this->baseUrl = $baseUrl;

        return $this;
    }

    public function setTimeout(int $timeout): self
    {
        if ($timeout < 0) {
            throw new \InvalidArgumentException('Timeout must be zero or a positive number of seconds');
        }

        $this->timeout = $timeout;

        return $this;
    }

    public function useSandbox(): self
    {
        $this->baseUrl = 'https://sandbox-api.coinmarketcap.com';

        return $this;
    }

    public function setHttpClient(ClientInterface $httpClient): self
    {
        $this->httpClient = $httpClient;

        return $this;
    }

    public function setRequestFactory(RequestFactoryInterface $requestFactory): self
    {
        $this->requestFactory = $requestFactory;

        return $this;
    }

    public function setStreamFactory(StreamFactoryInterface $streamFactory): self
    {
        $this->streamFactory = $streamFactory;

        return $this;
    }

    public function build(): Client
    {
        if ($this->apiKey === null || trim($this->apiKey) === '') {
            throw new \InvalidArgumentException('API key is required');
        }

        $httpClient = $this->httpClient ?? new GuzzleClient(['timeout' => $this->timeout]);
        $requestFactory = $this->requestFactory ?? new HttpFactory();
        $streamFactory = $this->streamFactory ?? new HttpFactory();

        $client = new HttpClient(
            $httpClient,
            $requestFactory,
            $streamFactory,
            $this->baseUrl,
            $this->apiKey
        );

        return new Client($client);
    }
}
