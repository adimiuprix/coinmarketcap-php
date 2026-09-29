<?php

declare(strict_types=1);

namespace adimiuprix\CoinMarketCap\Api;

use adimiuprix\CoinMarketCap\Http\HttpClient;

class GlobalMetrics
{
    public function __construct(
        private readonly HttpClient $httpClient
    ) {
    }

    /**
     * @param array<string, mixed> $params
     * @return array<string, mixed>
     */
    public function quotesLatest(array $params = []): array
    {
        return $this->httpClient->get('/v1/global-metrics/quotes/latest', $params);
    }

    /**
     * @param array<string, mixed> $params
     * @return array<string, mixed>
     */
    public function quotesHistorical(array $params = []): array
    {
        return $this->httpClient->get('/v1/global-metrics/quotes/historical', $params);
    }

    /**
     * @param array<string, mixed> $params
     * @return array<string, mixed>
     */
    public function fearAndGreedLatest(array $params = []): array
    {
        return $this->httpClient->get('/v3/fear-and-greed/latest', $params);
    }

    /**
     * @param array<string, mixed> $params
     * @return array<string, mixed>
     */
    public function fearAndGreedHistorical(array $params = []): array
    {
        return $this->httpClient->get('/v3/fear-and-greed/historical', $params);
    }

    /**
     * @param array<string, mixed> $params
     * @return array<string, mixed>
     */
    public function altcoinSeasonIndexLatest(array $params = []): array
    {
        return $this->httpClient->get('/v1/altcoin-season-index/latest', $params);
    }

    /**
     * @param array<string, mixed> $params
     * @return array<string, mixed>
     */
    public function altcoinSeasonIndexHistorical(array $params = []): array
    {
        return $this->httpClient->get('/v1/altcoin-season-index/historical', $params);
    }
}
