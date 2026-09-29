# CoinMarketCap API Client/SDK for Laravel & PHP

![CoinMarketCap PHP Laravel SDK](https://i.postimg.cc/XJxGwKQt/coinmarketcap-php-laravel-hero.jpg)

[![Latest Version on Packagist](https://img.shields.io/packagist/v/adimiuprix/coinmarketcap.svg?style=flat-square)](https://packagist.org/packages/adimiuprix/coinmarketcap)
[![PHP Version](https://img.shields.io/packagist/php-v/adimiuprix/coinmarketcap.svg?style=flat-square)](https://packagist.org/packages/adimiuprix/coinmarketcap)
[![License](https://img.shields.io/packagist/l/adimiuprix/coinmarketcap.svg?style=flat-square)](https://packagist.org/packages/adimiuprix/coinmarketcap)
[![CI](https://github.com/adimiuprix/coinmarketcap-php/actions/workflows/ci.yml/badge.svg?branch=main)](https://github.com/adimiuprix/coinmarketcap-php/actions/workflows/ci.yml)
[![Tests](https://github.com/adimiuprix/coinmarketcap-php/actions/workflows/test.yml/badge.svg?branch=main)](https://github.com/adimiuprix/coinmarketcap-php/actions/workflows/test.yml)
[![Coverage](https://github.com/adimiuprix/coinmarketcap-php/actions/workflows/coverage.yml/badge.svg?branch=main)](https://github.com/adimiuprix/coinmarketcap-php/actions/workflows/coverage.yml)
[![CodeQL](https://github.com/adimiuprix/coinmarketcap-php/actions/workflows/codeql.yml/badge.svg?branch=main)](https://github.com/adimiuprix/coinmarketcap-php/actions/workflows/codeql.yml)
[![Codecov](https://codecov.io/gh/adimiuprix/coinmarketcap-php/graph/badge.svg)](https://codecov.io/gh/adimiuprix/coinmarketcap-php)

A small PHP client for the [CoinMarketCap API](https://coinmarketcap.com/api/documentation/v1/). It works on its own or with Laravel through auto-discovery, a facade, and dependency injection.

Responses are returned as decoded associative arrays, preserving the API response shape and allowing every documented query parameter to be passed through without waiting for model updates.

## Requirements

- PHP 8.1 or newer
- Laravel 10, 11, or 12 when using the Laravel integration
- A CoinMarketCap API key — [create one here](https://coinmarketcap.com/api/)

## Install

```bash
composer require adimiuprix/coinmarketcap
```

Laravel discovers the service provider automatically. To publish the configuration file:

```bash
php artisan vendor:publish --tag=coinmarketcap-config
```

## Configuration

Set the API key in `.env`:

```env
COINMARKETCAP_API_KEY=your-api-key
COINMARKETCAP_BASE_URL=https://pro-api.coinmarketcap.com
COINMARKETCAP_TIMEOUT=30
COINMARKETCAP_USE_SANDBOX=false
```

`COINMARKETCAP_SANDBOX_URL` may be set when a custom sandbox endpoint is required. Timeout is measured in seconds; `0` disables Guzzle's timeout.

For a standalone client:

```php
use adimiuprix\CoinMarketCap\ClientBuilder;

$client = (new ClientBuilder())
    ->setApiKey('your-api-key')
    ->setTimeout(30)
    ->build();
```

Use `->useSandbox()` with a sandbox API key when testing.

## Quick start

Laravel facade:

```php
use adimiuprix\CoinMarketCap\Facades\CoinMarketCap;

$quotes = CoinMarketCap::cryptocurrency()->quotesLatest([
    'id' => '1,1027',
    'convert' => 'USD',
]);

foreach ($quotes['data'] as $asset) {
    echo $asset['symbol'] . ': $' . $asset['quote']['USD']['price'] . PHP_EOL;
}
```

For production lookups, CoinMarketCap recommends stable cryptocurrency IDs. Use `cryptocurrency()->map()` to obtain them.

Every API method accepts an array of query parameters. Refer to the [official API documentation](https://coinmarketcap.com/api/documentation/v1/) for valid parameters, plan availability, credit usage, and the precise response format.

## Supported endpoints

| Service | Methods |
| --- | --- |
| `cryptocurrency()` | `listingsLatest`, `listingsHistorical`, `listingsNew`, `quotesLatest`, `quotesHistorical`, `info`, `map`, `ohlcvLatest`, `ohlcvHistorical`, `categories`, `category`, `marketPairsLatest`, `pricePerformanceStatsLatest`, `simplePrice`, `airdrops`, `airdrop`, `trendingLatest`, `trendingGainersLosers`, `trendingMostVisited` |
| `exchange()` | `listingsLatest`, `quotesLatest`, `quotesHistorical`, `info`, `map`, `marketPairsLatest`, `assets` |
| `globalMetrics()` | `quotesLatest`, `quotesHistorical`, `fearAndGreedLatest`, `fearAndGreedHistorical`, `altcoinSeasonIndexLatest`, `altcoinSeasonIndexHistorical` |
| `tools()` | `priceConversion` |

For example, fetch the first ten cryptocurrencies by market cap:

```php
$listings = CoinMarketCap::cryptocurrency()->listingsLatest([
    'start' => 1,
    'limit' => 10,
    'convert' => 'USD',
]);
```

## Errors

All API and transport errors extend `ApiException`. Specific response status codes map to these types:

| Exception | HTTP status |
| --- | --- |
| `AuthenticationException` | 401 |
| `InvalidRequestException` | 400 |
| `NotFoundException` | 404 |
| `RateLimitException` | 429 |
| `ApiException` | Other API, transport, or malformed-response errors |

`ApiException` exposes `statusCode` and `getResponse()`. `RateLimitException` also provides `getRetryAfter()`, populated from CoinMarketCap's response or the standard `Retry-After` header when present.

```php
use adimiuprix\CoinMarketCap\Exception\RateLimitException;

try {
    $data = CoinMarketCap::cryptocurrency()->listingsLatest(['limit' => 10]);
} catch (RateLimitException $exception) {
    $seconds = $exception->getRetryAfter() ?? 60;

    // Queue a retry after $seconds instead of retrying immediately.
}
```

## Sandbox

```php
$client = (new ClientBuilder())
    ->setApiKey('your-sandbox-api-key')
    ->useSandbox()
    ->build();
```

## V3 migration

`cryptocurrency()->listingsLatest()`, `quotesLatest()`, and `quotesHistorical()` use CoinMarketCap's current V3 endpoints. V3 responses can differ from their V1/V2 predecessors; notably, latest quotes return `data` as an array of asset records rather than a symbol-keyed map. Update consumers before adopting this release and publish it as a new major version.

## Testing

```bash
composer test
composer phpstan
vendor/bin/php-cs-fixer fix --dry-run --diff
```

The test suite verifies builder validation, Laravel registration, HTTP encoding and error handling, and the endpoint paths exposed by every service method.

GitHub Actions runs the test suite on PHP 8.1–8.4, static analysis, code style checks, and PCOV coverage. The coverage workflow uploads `coverage.xml` to Codecov when the `CODECOV_TOKEN` repository secret is configured.

## License

[MIT](LICENSE)
