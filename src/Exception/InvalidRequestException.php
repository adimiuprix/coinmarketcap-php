<?php

declare(strict_types=1);

namespace adimiuprix\CoinMarketCap\Exception;

class InvalidRequestException extends ApiException
{
    /** @param array<string, mixed>|null $response */
    public function __construct(
        string $message = 'Invalid request parameters.',
        ?array $response = null
    ) {
        parent::__construct($message, 400, $response);
    }
}
