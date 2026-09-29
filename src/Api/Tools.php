<?php

declare(strict_types=1);

namespace adimiuprix\CoinMarketCap\Api;

use adimiuprix\CoinMarketCap\Http\HttpClient;

class Tools
{
    public function __construct(
        private readonly HttpClient $httpClient
    ) {
    }

    /**
     * @param array<string, mixed> $params
     * @return array<string, mixed>
     */
    public function priceConversion(array $params = []): array
    {
        return $this->httpClient->get('/v2/tools/price-conversion', $params);
    }
}
