# Changelog

All notable changes to this package are documented here. The format follows
[Keep a Changelog](https://keepachangelog.com/en/1.1.0/), versions follow [SemVer](https://semver.org/).

## [2.0.0]

Full rewrite. **Breaking:** the 1.x API (`Client`, `*Request` classes in the global namespace,
`Result{result, message}`) is removed. To migrate: `require 'vendor/autoload.php'` instead of `include './client/...'`,
`new CapMonsterClient(new ClientOptions($key))` instead of `new Client($key)`, request classes from
`CapMonsterCloud\Requests\` (a `proxy:` argument instead of separate `*Proxyless` classes), and `solve()` returns
the solution array and throws `CapMonsterException` instead of returning `Result{result, message}`.

**Renamed:** the Composer package is now `capmonstercloud/capmonster-client` (was `zennolab/capmonstercloud.client`,
which stays at 1.x) and the namespace is `CapMonsterCloud\`. Install with `composer require capmonstercloud/capmonster-client`.

### Added
- Composer package with PSR-4 autoloading (`CapMonsterCloud\`): works from `vendor/` in any project
  (fixes #12 "Not compatible using composer"). Requires PHP 8.1+, `ext-curl`, `ext-json`; no runtime dependencies.
- `CapMonsterClient`: `solve()` (createTask + polling with a per-type schedule), low-level `createTask()` /
  `getTaskResult()`, `getBalance()`. Errors are exceptions under `CapMonsterException`
  (`ValidationException`, `CreateTaskException` / `GetResultException` / `GetBalanceException` with `errorCode`,
  `TimeoutException`, `TransportException`, `UnexpectedHttpStatusException`, `MalformedResponseException`).
- `ClientOptions`: service URL, `softId`, per-request HTTP timeout, a proxy for the SDK's own requests
  (http/https/socks4/socks5), polling-schedule override. Pluggable `HttpTransport` (default: curl, keep-alive).
- Request types matching the current API documentation: reCAPTCHA v2 / v2 Enterprise / v3 / v3 Enterprise,
  reCAPTCHA click and Recognition ComplexImageTask, ImageToText, FunCaptcha, GeeTest v3/v4,
  Turnstile (standard, Cloudflare Challenge token / cf_clearance, Waiting Room), Amazon WAF (3 variants),
  Binance, MTCaptcha, Yidun, Prosopo, and CustomTask: DataDome, Imperva, TSPD, HUNT, Basilisk, TenDI, Alibaba,
  Friendly Captcha, Altcha.
- Local validation of requests before anything is sent; the API key and proxy passwords are masked in
  `var_dump()` / `print_r()`.

### Removed
- `HCaptchaRequest`, `ComplexImageHCaptchaRequest`, `ComplexImageFuncaptchaRequest`, `ComplexImageRequest`:
  no longer in the API documentation.
- `*Proxyless` task types: the API now uses one task type per captcha with an optional proxy.

### Fixed
- `ImageToTextTask` sends `capMonsterModule` / `case` (documented names) instead of `CapMonsterModule` / `Case`.
