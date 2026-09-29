<?php

declare(strict_types=1);

namespace adimiuprix\CoinMarketCap\Exception;

class AuthenticationException extends ApiException
{
    /** @param array<string, mixed>|null $response */
    public function __construct(
        string $message = 'Authentication failed. Invalid or missing API key.',
        ?array $response = null
    ) {
        parent::__construct($message, 401, $response);
    }
}
