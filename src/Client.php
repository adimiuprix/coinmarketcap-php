<?php

declare(strict_types=1);

namespace adimiuprix\CoinMarketCap;

use adimiuprix\CoinMarketCap\Api\Cryptocurrency;
use adimiuprix\CoinMarketCap\Api\Exchange;
use adimiuprix\CoinMarketCap\Api\GlobalMetrics;
use adimiuprix\CoinMarketCap\Api\Tools;
use adimiuprix\CoinMarketCap\Http\HttpClient;

class Client
{
    private ?Cryptocurrency $cryptocurrency = null;
    private ?Exchange $exchange = null;
    private ?GlobalMetrics $globalMetrics = null;
    private ?Tools $tools = null;

    public function __construct(
        private readonly HttpClient $httpClient
    ) {
    }

    public function cryptocurrency(): Cryptocurrency
    {
        if ($this->cryptocurrency === null) {
            $this->cryptocurrency = new Cryptocurrency($this->httpClient);
        }

        return $this->cryptocurrency;
    }

    public function exchange(): Exchange
    {
        if ($this->exchange === null) {
            $this->exchange = new Exchange($this->httpClient);
        }

        return $this->exchange;
    }

    public function globalMetrics(): GlobalMetrics
    {
        if ($this->globalMetrics === null) {
            $this->globalMetrics = new GlobalMetrics($this->httpClient);
        }

        return $this->globalMetrics;
    }

    public function tools(): Tools
    {
        if ($this->tools === null) {
            $this->tools = new Tools($this->httpClient);
        }

        return $this->tools;
    }
}
