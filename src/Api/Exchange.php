<?php

declare(strict_types=1);

namespace adimiuprix\CoinMarketCap\Api;

use adimiuprix\CoinMarketCap\Http\HttpClient;

class Exchange
{
    public function __construct(
        private readonly HttpClient $httpClient
    ) {
    }

    /**
     * @param array<string, mixed> $params
     * @return array<string, mixed>
     */
    public function listingsLatest(array $params = []): array
    {
        return $this->httpClient->get('/v1/exchange/listings/latest', $params);
    }

    /**
     * @param array<string, mixed> $params
     * @return array<string, mixed>
     */
    public function quotesLatest(array $params = []): array
    {
        return $this->httpClient->get('/v1/exchange/quotes/latest', $params);
    }

    /**
     * @param array<string, mixed> $params
     * @return array<string, mixed>
     */
    public function quotesHistorical(array $params = []): array
    {
        return $this->httpClient->get('/v1/exchange/quotes/historical', $params);
    }

    /**
     * @param array<string, mixed> $params
     * @return array<string, mixed>
     */
    public function info(array $params = []): array
    {
        return $this->httpClient->get('/v1/exchange/info', $params);
    }

    /**
     * @param array<string, mixed> $params
     * @return array<string, mixed>
     */
    public function map(array $params = []): array
    {
        return $this->httpClient->get('/v1/exchange/map', $params);
    }

    /**
     * @param array<string, mixed> $params
     * @return array<string, mixed>
     */
    public function marketPairsLatest(array $params = []): array
    {
        return $this->httpClient->get('/v1/exchange/market-pairs/latest', $params);
    }

    /**
     * @param array<string, mixed> $params
     * @return array<string, mixed>
     */
    public function assets(array $params = []): array
    {
        return $this->httpClient->get('/v1/exchange/assets', $params);
    }
}
