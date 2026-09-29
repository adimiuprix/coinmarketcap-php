<?php

declare(strict_types=1);

namespace adimiuprix\CoinMarketCap\Exception;

class RateLimitException extends ApiException
{
    /** @param array<string, mixed>|null $response */
    public function __construct(
        string $message = 'Rate limit exceeded.',
        public readonly ?int $retryAfter = null,
        ?array $response = null
    ) {
        parent::__construct($message, 429, $response);
    }

    public function getRetryAfter(): ?int
    {
        return $this->retryAfter;
    }
}
