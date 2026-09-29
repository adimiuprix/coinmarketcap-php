<?php

declare(strict_types=1);

namespace adimiuprix\CoinMarketCap\Tests\Unit;

use PHPUnit\Framework\TestCase;
use adimiuprix\CoinMarketCap\Client;
use adimiuprix\CoinMarketCap\ClientBuilder;

class ClientBuilderTest extends TestCase
{
    public function test_can_build_client_with_api_key(): void
    {
        $builder = new ClientBuilder();
        $client = $builder
            ->setApiKey('test-key')
            ->build();

        $this->assertInstanceOf(Client::class, $client);
    }

    public function test_throws_exception_when_api_key_is_missing(): void
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('API key is required');

        $builder = new ClientBuilder();
        $builder->build();
    }

    public function test_throws_exception_when_api_key_is_empty(): void
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('API key is required');

        (new ClientBuilder())
            ->setApiKey('   ')
            ->build();
    }

    public function test_rejects_an_invalid_base_url(): void
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('Base URL must be a valid HTTP(S) URL');

        (new ClientBuilder())->setBaseUrl('ftp://example.test');
    }

    public function test_rejects_a_negative_timeout(): void
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('Timeout must be zero or a positive number of seconds');

        (new ClientBuilder())->setTimeout(-1);
    }

    public function test_can_set_custom_base_url(): void
    {
        $builder = new ClientBuilder();
        $client = $builder
            ->setApiKey('test-key')
            ->setBaseUrl('https://custom-api.example.com')
            ->build();

        $this->assertInstanceOf(Client::class, $client);
    }

    public function test_can_use_sandbox(): void
    {
        $builder = new ClientBuilder();
        $client = $builder
            ->setApiKey('test-key')
            ->useSandbox()
            ->build();

        $this->assertInstanceOf(Client::class, $client);
    }

    public function test_can_set_timeout(): void
    {
        $builder = new ClientBuilder();
        $client = $builder
            ->setApiKey('test-key')
            ->setTimeout(60)
            ->build();

        $this->assertInstanceOf(Client::class, $client);
    }
}
