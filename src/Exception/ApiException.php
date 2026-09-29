<?php

declare(strict_types=1);

namespace adimiuprix\CoinMarketCap\Exception;

use Exception;
use Throwable;

class ApiException extends Exception
{
    /** @param array<string, mixed>|null $response */
    public function __construct(
        string $message,
        public readonly int $statusCode,
        public readonly ?array $response = null,
        ?Throwable $previous = null
    ) {
        parent::__construct($message, $statusCode, $previous);
    }

    /** @return array<string, mixed>|null */
    public function getResponse(): ?array
    {
        return $this->response;
    }
}
