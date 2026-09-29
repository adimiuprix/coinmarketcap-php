<?php

declare(strict_types=1);

namespace adimiuprix\CoinMarketCap\Model;

class Exchange
{
    /** @param array<string, mixed>|null $quote */
    public function __construct(
        public readonly int $id,
        public readonly string $name,
        public readonly string $slug,
        public readonly int $numMarketPairs,
        public readonly string $lastUpdated,
        public readonly ?array $quote = null
    ) {
    }
}
