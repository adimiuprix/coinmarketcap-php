<?php

declare(strict_types=1);

namespace adimiuprix\CoinMarketCap\Api;

use adimiuprix\CoinMarketCap\Http\HttpClient;

class Cryptocurrency
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
        return $this->httpClient->get('/v3/cryptocurrency/listings/latest', $params);
    }

    /**
     * @param array<string, mixed> $params
     * @return array<string, mixed>
     */
    public function listingsHistorical(array $params = []): array
    {
        return $this->httpClient->get('/v1/cryptocurrency/listings/historical', $params);
    }

    /**
     * @param array<string, mixed> $params
     * @return array<string, mixed>
     */
    public function listingsNew(array $params = []): array
    {
        return $this->httpClient->get('/v1/cryptocurrency/listings/new', $params);
    }

    /**
     * @param array<string, mixed> $params
     * @return array<string, mixed>
     */
    public function quotesLatest(array $params = []): array
    {
        return $this->httpClient->get('/v3/cryptocurrency/quotes/latest', $params);
    }

    /**
     * @param array<string, mixed> $params
     * @return array<string, mixed>
     */
    public function quotesHistorical(array $params = []): array
    {
        return $this->httpClient->get('/v3/cryptocurrency/quotes/historical', $params);
    }

    /**
     * @param array<string, mixed> $params
     * @return array<string, mixed>
     */
    public function info(array $params = []): array
    {
        return $this->httpClient->get('/v2/cryptocurrency/info', $params);
    }

    /**
     * @param array<string, mixed> $params
     * @return array<string, mixed>
     */
    public function map(array $params = []): array
    {
        return $this->httpClient->get('/v1/cryptocurrency/map', $params);
    }

    /**
     * @param array<string, mixed> $params
     * @return array<string, mixed>
     */
    public function ohlcvLatest(array $params = []): array
    {
        return $this->httpClient->get('/v2/cryptocurrency/ohlcv/latest', $params);
    }

    /**
     * @param array<string, mixed> $params
     * @return array<string, mixed>
     */
    public function ohlcvHistorical(array $params = []): array
    {
        return $this->httpClient->get('/v2/cryptocurrency/ohlcv/historical', $params);
    }

    /**
     * @param array<string, mixed> $params
     * @return array<string, mixed>
     */
    public function categories(array $params = []): array
    {
        return $this->httpClient->get('/v1/cryptocurrency/categories', $params);
    }

    /**
     * @param array<string, mixed> $params
     * @return array<string, mixed>
     */
    public function category(array $params = []): array
    {
        return $this->httpClient->get('/v1/cryptocurrency/category', $params);
    }

    /**
     * @param array<string, mixed> $params
     * @return array<string, mixed>
     */
    public function marketPairsLatest(array $params = []): array
    {
        return $this->httpClient->get('/v2/cryptocurrency/market-pairs/latest', $params);
    }

    /**
     * @param array<string, mixed> $params
     * @return array<string, mixed>
     */
    public function pricePerformanceStatsLatest(array $params = []): array
    {
        return $this->httpClient->get('/v2/cryptocurrency/price-performance-stats/latest', $params);
    }

    /**
     * @param array<string, mixed> $params
     * @return array<string, mixed>
     */
    public function simplePrice(array $params = []): array
    {
        return $this->httpClient->get('/v1/simple/price', $params);
    }

    /**
     * @param array<string, mixed> $params
     * @return array<string, mixed>
     */
    public function airdrops(array $params = []): array
    {
        return $this->httpClient->get('/v1/cryptocurrency/airdrops', $params);
    }

    /**
     * @param array<string, mixed> $params
     * @return array<string, mixed>
     */
    public function airdrop(array $params = []): array
    {
        return $this->httpClient->get('/v1/cryptocurrency/airdrop', $params);
    }

    /**
     * @param array<string, mixed> $params
     * @return array<string, mixed>
     */
    public function trendingLatest(array $params = []): array
    {
        return $this->httpClient->get('/v1/cryptocurrency/trending/latest', $params);
    }

    /**
     * @param array<string, mixed> $params
     * @return array<string, mixed>
     */
    public function trendingGainersLosers(array $params = []): array
    {
        return $this->httpClient->get('/v1/cryptocurrency/trending/gainers-losers', $params);
    }

    /**
     * @param array<string, mixed> $params
     * @return array<string, mixed>
     */
    public function trendingMostVisited(array $params = []): array
    {
        return $this->httpClient->get('/v1/cryptocurrency/trending/most-visited', $params);
    }
}
