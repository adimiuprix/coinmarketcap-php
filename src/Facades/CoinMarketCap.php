<?php

declare(strict_types=1);

namespace adimiuprix\CoinMarketCap\Facades;

use Illuminate\Support\Facades\Facade;
use adimiuprix\CoinMarketCap\Client;

class CoinMarketCap extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return Client::class;
    }
}
