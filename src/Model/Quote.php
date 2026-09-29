<?php

declare(strict_types=1);

namespace adimiuprix\CoinMarketCap\Model;

class Quote
{
    public function __construct(
        public readonly float $price,
        public readonly float $volume24h,
        public readonly float $volumeChange24h,
        public readonly float $percentChange1h,
        public readonly float $percentChange24h,
        public readonly float $percentChange7d,
        public readonly float $percentChange30d,
        public readonly float $marketCap,
        public readonly float $marketCapDominance,
        public readonly float $fullyDilutedMarketCap,
        public readonly string $lastUpdated
    ) {
    }
}
