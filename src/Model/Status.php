<?php

declare(strict_types=1);

namespace adimiuprix\CoinMarketCap\Model;

class Status
{
    public function __construct(
        public readonly string $timestamp,
        public readonly int $errorCode,
        public readonly ?string $errorMessage,
        public readonly int $elapsed,
        public readonly int $creditCount
    ) {
    }
}
