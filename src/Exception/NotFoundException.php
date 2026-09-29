<?php

declare(strict_types=1);

namespace adimiuprix\CoinMarketCap\Exception;

class NotFoundException extends ApiException
{
    /** @param array<string, mixed>|null $response */
    public function __construct(
        string $message = 'Resource not found.',
        ?array $response = null
    ) {
        parent::__construct($message, 404, $response);
    }
}
