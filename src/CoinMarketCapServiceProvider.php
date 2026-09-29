<?php

declare(strict_types=1);

namespace adimiuprix\CoinMarketCap;

use Illuminate\Support\ServiceProvider;

class CoinMarketCapServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->mergeConfigFrom(
            __DIR__ . '/../config/coinmarketcap.php',
            'coinmarketcap'
        );

        $this->app->singleton(Client::class, function ($app) {
            $config = $app['config']['coinmarketcap'];

            $builder = new ClientBuilder();
            $builder->setApiKey($config['api_key'] ?? '');

            if ($config['use_sandbox'] ?? false) {
                $builder->setBaseUrl($config['sandbox_url'] ?? 'https://sandbox-api.coinmarketcap.com');
            } else {
                $builder->setBaseUrl($config['base_url'] ?? 'https://pro-api.coinmarketcap.com');
            }

            $builder->setTimeout($config['timeout'] ?? 30);

            return $builder->build();
        });
    }

    public function boot(): void
    {
        if ($this->app->runningInConsole()) {
            $this->publishes([
                __DIR__ . '/../config/coinmarketcap.php' => config_path('coinmarketcap.php'),
            ], 'coinmarketcap-config');
        }
    }
}
