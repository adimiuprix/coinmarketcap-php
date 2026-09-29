<?php

declare(strict_types=1);

namespace adimiuprix\CoinMarketCap\Tests\Feature;

use adimiuprix\CoinMarketCap\Client;
use adimiuprix\CoinMarketCap\Tests\TestCase;

class ServiceProviderTest extends TestCase
{
    public function test_client_is_registered_in_container(): void
    {
        $client = $this->app->make(Client::class);

        $this->assertInstanceOf(Client::class, $client);
    }

    public function test_client_is_singleton(): void
    {
        $client1 = $this->app->make(Client::class);
        $client2 = $this->app->make(Client::class);

        $this->assertSame($client1, $client2);
    }
}
