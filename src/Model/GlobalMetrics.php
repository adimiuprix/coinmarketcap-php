<?php

declare(strict_types=1);

namespace adimiuprix\CoinMarketCap\Model;

class GlobalMetrics
{
    /** @param array<string, mixed>|null $quote */
    public function __construct(
        public readonly int $activeCryptocurrencies,
        public readonly int $totalCryptocurrencies,
        public readonly int $activeExchanges,
        public readonly int $activeMarketPairs,
        public readonly float $btcDominance,
        public readonly float $ethDominance,
        public readonly string $lastUpdated,
        public readonly ?array $quote = null
    ) {
    }
}
