# Changelog

All notable changes to `adimiuprix/coinmarketcap` are documented in this file.

## [Unreleased]

### Added

- Cryptocurrency methods: `listingsNew`, `category`, `marketPairsLatest`, `pricePerformanceStatsLatest`, `simplePrice`, `airdrops`, `airdrop`, and `trendingMostVisited`.
- Exchange `assets` method.
- Global Metrics methods for Fear and Greed and the Altcoin Season Index.
- Regression coverage for every supported endpoint and the HTTP transport layer.
- GitHub Actions workflows for CI, a PHP 8.1–8.4 test matrix, PCOV coverage with Codecov upload, and CodeQL analysis of workflow security.

### Changed

- `cryptocurrency()->listingsLatest()`, `quotesLatest()`, and `quotesHistorical()` now use CoinMarketCap's V3 endpoints.
- Guzzle is now a production dependency, matching the default HTTP client created by `ClientBuilder`.
- The Laravel configuration accepts `COINMARKETCAP_SANDBOX_URL` and converts `COINMARKETCAP_TIMEOUT` to an integer.
- The service provider honours a configured sandbox URL.

### Fixed

- Empty API keys and invalid client configuration now fail before a request is attempted.
- Invalid JSON responses preserve the HTTP status and are reported as `ApiException` instances.
- `RateLimitException` reads `retry_after` from the API response and falls back to the `Retry-After` header.

### Breaking

- V3 cryptocurrency responses may have a different `data` shape than V1/V2 responses. Release these changes as v2.0.0.

## [v1.0.2] - 2026-01-30

### Added

- Laravel 12 support.

### Changed

- Updated `illuminate/support` to support `^10.0|^11.0|^12.0`.
- Updated `orchestra/testbench` to support `^8.0|^9.0|^10.0`.

## [v1.0.0] - 2026-01-29

### Added

- Initial CoinMarketCap API client for PHP and Laravel.
- Cryptocurrency, exchange, global metrics, and tools services.
- PSR-18 HTTP abstraction, Laravel provider and facade, exception types, and configuration support.

[Unreleased]: https://github.com/adimiuprix/coinmarketcap/compare/v1.0.2...HEAD
[v1.0.2]: https://github.com/adimiuprix/coinmarketcap/releases/tag/v1.0.2
[v1.0.0]: https://github.com/adimiuprix/coinmarketcap/releases/tag/v1.0.0
