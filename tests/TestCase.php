<?php

declare(strict_types=1);

namespace adimiuprix\CoinMarketCap\Tests;

use Orchestra\Testbench\TestCase as Orchestra;
use adimiuprix\CoinMarketCap\CoinMarketCapServiceProvider;

abstract class TestCase extends Orchestra
{
    protected function getPackageProviders($app): array
    {
        return [
            CoinMarketCapServiceProvider::class,
        ];
    }

    protected function getEnvironmentSetUp($app): void
    {
        $app['config']->set('coinmarketcap.api_key', 'test-api-key');
        $app['config']->set('coinmarketcap.base_url', 'https://pro-api.coinmarketcap.com');
        $app['config']->set('coinmarketcap.timeout', 30);
        $app['config']->set('coinmarketcap.use_sandbox', false);
    }
}
