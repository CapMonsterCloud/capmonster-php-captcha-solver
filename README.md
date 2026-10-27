# CapMonster Cloud PHP SDK: PHP Captcha Solver & Captcha Bypass for reCAPTCHA, Cloudflare Turnstile and GeeTest

> **🔄 Migrating from 2Captcha or Capsolver?** CapMonster Cloud is a fast, cost-effective **2captcha alternative** and **capsolver alternative** for PHP: reCAPTCHA v3 from **$0.90 per 1,000 solves**, which is **up to 38% cheaper than 2Captcha** and **10% cheaper than Capsolver**, with an API response time of **≤ 2 s**. Already have software built for 2Captcha? CapMonster Cloud [supports the 2Captcha API format](https://docs.capmonster.cloud/docs/external-services/?utm_source=github&utm_medium=referral&utm_campaign=php_repo_readme). Moving your PHP code takes just a few lines: see the **Migration examples** below. Full comparison and disclaimer are in the comparison section below.

<p align="center">
  <a href="https://capmonster.cloud/en/?utm_source=github&utm_medium=referral&utm_campaign=php_repo_readme">
    <img src="https://img.shields.io/badge/CapMonster%20Cloud-PHP%20Captcha%20Solver-00B2FF?style=for-the-badge&logo=php&logoColor=white" alt="CapMonster Cloud PHP SDK - captcha solver and captcha bypass API client" height="40">
  </a>
</p>

<p align="center">
  <strong>Fast, AI-driven CAPTCHA solving client for PHP. Seamlessly bypass Cloudflare Turnstile, reCAPTCHA v2/v3/Enterprise and GeeTest in web scraping and browser automation.</strong>
</p>

<p align="center">
  <a href="https://github.com/CapMonsterCloud/capmonster-php-captcha-solver/stargazers"><img src="https://img.shields.io/github/stars/CapMonsterCloud/capmonster-php-captcha-solver?style=flat-square&color=yellow" alt="GitHub Stars"></a>
  <a href="https://github.com/CapMonsterCloud/capmonster-php-captcha-solver/network/members"><img src="https://img.shields.io/github/forks/CapMonsterCloud/capmonster-php-captcha-solver?style=flat-square" alt="GitHub Forks"></a>
  <a href="./LICENSE"><img src="https://img.shields.io/badge/License-MIT-orange.svg?style=flat-square" alt="License: MIT"></a>
</p>

---

PHP client library for the [CapMonster Cloud](https://capmonster.cloud/en/?utm_source=github&utm_medium=referral&utm_campaign=php_repo_readme) captcha solving API. Use this PHP captcha solver for captcha bypass: bypass reCAPTCHA v2, reCAPTCHA v3, reCAPTCHA v2 Enterprise and Cloudflare Turnstile, solve GeeTest, reCAPTCHA image-click and text (image-to-text) captchas in web scraping, browser automation and backend scripts: send a request class to the `solve` method and get a ready solution. As the official CapMonster SDK for PHP, it is a cost-effective 2captcha alternative and capsolver alternative.

**[👉 Get your Free API Key & Free Trial Balance on CapMonster Cloud](https://dash.capmonster.cloud/Account/SignUp?utm_source=github&utm_medium=referral&utm_campaign=php_repo_readme)**

---

## ⚡ Highlights

- ⚡ **Fast solving:** AI-based recognition, on average between 1 and 30 seconds depending on the captcha type.
- 🤖 **Zero human workers:** 100% automated machine learning pipeline with no manual latencies.
- 🛡️ **Modern anti-bot bypass:** Support for Cloudflare Turnstile, reCAPTCHA Enterprise, GeeTest and FunCaptcha.
- 🧩 **One call to solve a captcha:** create a request object and pass it to `$client->solve()`.
- 🔁 **Simple results and typed errors:** `solve()` returns the solution as an array; failures throw exceptions from one `CapMonsterException` hierarchy.
- 💰 **Cost-effective:** Low-cost pricing structure based on pay-as-you-go balance.
- 📉 **Cheaper than alternatives:** reCAPTCHA v3 from $0.90 per 1,000 solves, versus $1.45 at 2Captcha and $1.00 at Capsolver.
- 🔄 **Easy migration:** replace your 2Captcha or Capsolver solver call and keep the rest of your scraper or bot unchanged.
- 📖 **Official docs:** request types follow the [CapMonster Cloud API documentation](https://docs.capmonster.cloud/docs/captchas/?utm_source=github&utm_medium=referral&utm_campaign=php_repo_readme).

---

## 📊 CapMonster Cloud vs 2Captcha vs Capsolver

Looking for a 2captcha alternative or a capsolver alternative for PHP web scraping and automation? Compare price, speed and success rate.

### reCAPTCHA v3

| Parameter | CapMonster Cloud | 2Captcha | CapSolver | DeathByCaptcha |
| :--- | :---: | :---: | :---: | :---: |
| Price per 1,000 solves (from) | **$0.90** | $1.45 | $1.00 | $2.89 |
| API response time | **≤ 2 s** | ≤ 2 s | < 3 s | ≤ 3 s |
| Solving success rate | **up to 99%** | ~77–92% | ~95–98% | ~64–90% |

*Disclaimer: 2Captcha, CapSolver, and DeathByCaptcha are trademarks of their respective owners. CapMonster Cloud is not affiliated with these entities. Performance metrics and pricing comparisons are based on publicly available data and internal benchmarking conducted on 27.09.2026.*

### Cloudflare Turnstile

| Parameter | CapMonster Cloud | 2Captcha | Anti-Captcha | DeathByCaptcha |
| :--- | :---: | :---: | :---: | :---: |
| Price per 1,000 solves (from) | **$1.30** | $1.45 | $2.00 | $2.89 |
| Time to solve | **6.24 s\*** | 16.9 s | 20.33 s | ~13 s |
| Solving success rate | **99%\*** | 96% | 96% | 96% |

*Disclaimer: 2Captcha, Anti-Captcha, and DeathByCaptcha are trademarks of their respective owners. CapMonster Cloud is not affiliated with these entities. Performance metrics and pricing comparisons are based on publicly available data and internal benchmarking as of 12.10.2026.*

**What this means in practice:**

- **reCAPTCHA v3:** $0.90 versus $1.45 at 2Captcha is up to 38% cheaper, and $0.90 versus $1.00 at Capsolver is 10% cheaper.
- **Cloudflare Turnstile:** $1.30 versus $1.45 at 2Captcha is 10% cheaper, and a solving time of 6.24 s versus 16.9 s is more than 60% faster.

---

## 📦 Installation

Install the package with [Composer](https://getcomposer.org/). PHP 8.1 or newer with the `curl` and `json` extensions is required.

```bash
composer require zennolab/capmonstercloud.client
```

Then load Composer's autoloader with `require 'vendor/autoload.php';` as shown below.

---

## 🚀 Quick Start: Solve and Bypass Captchas in PHP

```php
<?php
require 'vendor/autoload.php';

use Zennolab\CapMonsterCloud\CapMonsterClient;
use Zennolab\CapMonsterCloud\ClientOptions;
use Zennolab\CapMonsterCloud\Requests\ImageToTextRequest;
use Zennolab\CapMonsterCloud\Requests\RecaptchaV2Request;
use Zennolab\CapMonsterCloud\Requests\TurnstileRequest;

$client = new CapMonsterClient(new ClientOptions('your_client_key'));

// check balance
echo $client->getBalance(), PHP_EOL;

// solve image captcha
$imageResult = $client->solve(new ImageToTextRequest('base64_captcha_image'));
echo $imageResult['text'], PHP_EOL;

// solve reCAPTCHA v2 (without proxy)
$recaptchaV2Result = $client->solve(new RecaptchaV2Request(
    'https://lessons.zennolab.com/captchas/recaptcha/v2_simple.php?level=high',
    '6Lcg7CMUAAAAANphynKgn9YAgA4tQ2KI_iqRyTwd',
));
echo $recaptchaV2Result['gRecaptchaResponse'], PHP_EOL;

// solve Cloudflare Turnstile (without proxy)
$turnstileResult = $client->solve(TurnstileRequest::standard(
    'https://target-website.com/login',
    '0x4AAAAAAABnPIDnK2k_e-2',
));
echo $turnstileResult['token'], PHP_EOL;
```

### Response format

`solve()` returns the `solution` object of the server response as an associative array, for example `['gRecaptchaResponse' => '...']` for reCAPTCHA or `['text' => '...']` for an image. If the task cannot be solved, `solve()` throws an exception:

```php
use Zennolab\CapMonsterCloud\Exception\CapMonsterException;
use Zennolab\CapMonsterCloud\Exception\GetResultException;
use Zennolab\CapMonsterCloud\Exception\TimeoutException;

try {
    $solution = $client->solve($request);
} catch (GetResultException $e) {
    // the API returned an error, e.g. $e->errorCode === 'ERROR_CAPTCHA_UNSOLVABLE'
} catch (TimeoutException $e) {
    // no solution within the maximum waiting time of this captcha type
} catch (CapMonsterException $e) {
    // anything else: invalid request, network error, wrong key, zero balance...
}
```

---

## 🔄 Migrating from 2Captcha or Capsolver

Already use 2Captcha or Capsolver in your PHP scraper or bot? Replace only the solver call and keep the rest of your code unchanged. CapMonster Cloud follows the same flow: send the website URL and sitekey, receive the token.

### Before: 2Captcha (2captcha-php)

```php
// Before: 2Captcha
$solver = new \TwoCaptcha\TwoCaptcha('YOUR_2CAPTCHA_API_KEY');
$result = $solver->recaptcha([
    'sitekey' => 'YOUR_SITE_KEY',
    'url'     => 'https://target-website.com/page-with-recaptcha',
]);
$token = $result->code;
```

### Migrating from Capsolver

CapMonster Cloud uses the same `createTask` / `getTaskResult` flow, so if you call the Capsolver HTTP API directly from PHP, you can switch to the CapMonster Cloud PHP SDK or follow the [API Methods Reference](https://docs.capmonster.cloud/docs/methods/?utm_source=github&utm_medium=referral&utm_campaign=php_repo_readme).

### After: CapMonster Cloud

```php
<?php
require 'vendor/autoload.php';

use Zennolab\CapMonsterCloud\CapMonsterClient;
use Zennolab\CapMonsterCloud\ClientOptions;
use Zennolab\CapMonsterCloud\Exception\CapMonsterException;
use Zennolab\CapMonsterCloud\Requests\RecaptchaV2Request;

$client = new CapMonsterClient(new ClientOptions('YOUR_CAPMONSTER_API_KEY'));

$request = new RecaptchaV2Request(
    'https://target-website.com/page-with-recaptcha',
    'YOUR_SITE_KEY',
);

try {
    $token = $client->solve($request)['gRecaptchaResponse'];
    echo $token;
} catch (CapMonsterException $e) {
    echo $e->getMessage();
}
```

Only the client, the API key and the request class change: the token is used exactly as before, so the rest of your scraper stays the same.

### Keep existing 2Captcha-based software

Software that already speaks the 2Captcha API can work with CapMonster Cloud without code changes. See the [guide for connecting other services' APIs](https://docs.capmonster.cloud/docs/external-services/?utm_source=github&utm_medium=referral&utm_campaign=php_repo_readme).

---

## 🛡️ Supported CAPTCHA Types: Bypass reCAPTCHA, Cloudflare Turnstile, GeeTest and More

Use this PHP captcha solver for captcha bypass in web scraping and automation: reCAPTCHA bypass (v2, v2 Enterprise, v3), Cloudflare Turnstile solver, GeeTest solver, FunCaptcha solving and image-to-text recognition.

All task types follow the official [CapMonster Cloud API documentation](https://docs.capmonster.cloud/docs/captchas/?utm_source=github&utm_medium=referral&utm_campaign=php_repo_readme):

| Captcha type | PHP request classes | Documentation |
| :--- | :--- | :--- |
| reCAPTCHA v2 | `RecaptchaV2Request` (optional proxy) | [RecaptchaV2Task](https://docs.capmonster.cloud/docs/captchas/no-captcha-task/?utm_source=github&utm_medium=referral&utm_campaign=php_repo_readme) |
| reCAPTCHA v2 Enterprise | `RecaptchaV2EnterpriseRequest` (optional proxy) | [RecaptchaV2EnterpriseTask](https://docs.capmonster.cloud/docs/captchas/recaptcha-v2-enterprise-task/?utm_source=github&utm_medium=referral&utm_campaign=php_repo_readme) |
| reCAPTCHA v3 | `RecaptchaV3ProxylessRequest` | [RecaptchaV3TaskProxyless](https://docs.capmonster.cloud/docs/captchas/recaptcha-v3-task/?utm_source=github&utm_medium=referral&utm_campaign=php_repo_readme) |
| reCAPTCHA image-click (ComplexImageTask) | `RecaptchaComplexImageTaskRequest` | [ComplexImageTask reCAPTCHA](https://docs.capmonster.cloud/docs/captchas/recaptcha-click/?utm_source=github&utm_medium=referral&utm_campaign=php_repo_readme) |
| Cloudflare Turnstile | `TurnstileRequest::standard()` and other `TurnstileRequest` factories | [TurnstileTask](https://docs.capmonster.cloud/docs/captchas/turnstile-task/?utm_source=github&utm_medium=referral&utm_campaign=php_repo_readme) |
| GeeTest | `GeetestRequest::v3()`, `GeetestRequest::v4()` | [GeeTestTask](https://docs.capmonster.cloud/docs/captchas/geetest-task/?utm_source=github&utm_medium=referral&utm_campaign=php_repo_readme) |
| Text captcha (image to text) | `ImageToTextRequest` | [ImageToTextTask](https://docs.capmonster.cloud/docs/captchas/image-to-text/?utm_source=github&utm_medium=referral&utm_campaign=php_repo_readme) |

---

## 🛠️ How It Works

```text
[ Your PHP script ]
         │
         ▼
[ Extract sitekey & website URL ]
         │
         ▼
[ CapMonster Cloud PHP SDK ] ──► createTask API request
         │
         ▼
[ Neural Network Processing ] ──► Poll getTaskResult until the task is ready
         │
         ▼
[ Receive Token & Autosubmit ] ──► Complete bypass
```

---

## ⚙️ Best Practices

- **Use tokens promptly:** CAPTCHA tokens expire quickly (a reCAPTCHA v2 token is valid for 60 seconds after solving), so submit or inject the solution immediately after receiving the response.
- **Keep session context consistent:** When a task uses a proxy, use the same proxy in your scraping or browser session and in the CapMonster task.
- **Use the right task type:** Choose the request type that matches the target protection and follow the required parameters in the [documentation](https://docs.capmonster.cloud/docs/captchas/?utm_source=github&utm_medium=referral&utm_campaign=php_repo_readme).
- **Monitor your balance:** Keep your account balance positive in the [Dashboard](https://dash.capmonster.cloud/?utm_source=github&utm_medium=referral&utm_campaign=php_repo_readme) and handle API errors in your code.

---

## 💡 Use Cases

- **Web scraping and data collection** from websites you are authorized to access, without manual captcha solving.
- **Browser automation:** get the solution token in PHP and inject it into the page with your automation tool.
- **Automated testing (QA)** of your own website and its captcha-protected forms.
- **Backend services** that need a captcha solving API inside a PHP application.

---

## ❓ FAQ

**Is CapMonster Cloud a 2Captcha alternative for PHP?**
Yes. It is a captcha solving service with an official PHP SDK. For reCAPTCHA v3 it starts at $0.90 per 1,000 solves versus $1.45 at 2Captcha, which is up to 38% cheaper. For Cloudflare Turnstile it starts at $1.30 versus $1.45, which is 10% cheaper.

**Is CapMonster Cloud a Capsolver alternative for PHP?**
Yes. For reCAPTCHA v3 it is 10% cheaper than Capsolver ($0.90 versus $1.00 per 1,000 solves) and shows an API response time of ≤ 2 s versus < 3 s. See the comparison table above and its disclaimer.

**How do I migrate from 2Captcha or Capsolver to CapMonster Cloud in PHP?**
Create a `CapMonsterClient` with your CapMonster Cloud API key, build the matching request class and call `$client->solve()`. See the migration examples above. Software that already supports the 2Captcha API can also be connected through the [CapMonster Cloud guide](https://docs.capmonster.cloud/docs/external-services/?utm_source=github&utm_medium=referral&utm_campaign=php_repo_readme).

**Is there an official PHP captcha solver from CapMonster Cloud?**
Yes. This repository is the official PHP client library for the CapMonster Cloud captcha solving service.

**How do I bypass reCAPTCHA v2 in PHP?**
Create a `CapMonsterClient` with your API key, build a `RecaptchaV2Request` with the page URL and sitekey, call `$client->solve($request)` and read the token from the `gRecaptchaResponse` key of the returned array. See the [reCAPTCHA v2 documentation](https://docs.capmonster.cloud/docs/captchas/no-captcha-task/?utm_source=github&utm_medium=referral&utm_campaign=php_repo_readme).

**How do I bypass Cloudflare Turnstile in PHP?**
Use `TurnstileRequest::standard()` (pass `proxy:` to solve through your proxy) from the supported types table above. According to the [Turnstile documentation](https://docs.capmonster.cloud/docs/captchas/turnstile-task/?utm_source=github&utm_medium=referral&utm_campaign=php_repo_readme), all Turnstile subtypes are supported automatically: manual, non-interactive and invisible.

**Which captcha types does the PHP SDK solve?**
reCAPTCHA v2, v2 Enterprise and v3, Cloudflare Turnstile, GeeTest, image-to-text and reCAPTCHA image-click tasks. See the supported types table above and the [Supported Captchas Overview](https://docs.capmonster.cloud/docs/captchas/?utm_source=github&utm_medium=referral&utm_campaign=php_repo_readme).

**How do I get an API key?**
Register on CapMonster Cloud, top up your balance and copy your API key. The [Getting Started Guide](https://docs.capmonster.cloud/docs/getting-start/?utm_source=github&utm_medium=referral&utm_campaign=php_repo_readme) describes each step.

**How fast are captchas solved?**
On average between 1 and 30 seconds, depending on the captcha type. More details are in the [CapMonster Cloud FAQ](https://docs.capmonster.cloud/docs/faq/?utm_source=github&utm_medium=referral&utm_campaign=php_repo_readme).

**How long is a reCAPTCHA v2 solution valid?**
According to the [reCAPTCHA v2 documentation](https://docs.capmonster.cloud/docs/captchas/no-captcha-task/?utm_source=github&utm_medium=referral&utm_campaign=php_repo_readme), the returned token is valid for 60 seconds after the captcha is solved, so use it right away.

**Where is the full API reference?**
See the [API Methods Reference](https://docs.capmonster.cloud/docs/methods/?utm_source=github&utm_medium=referral&utm_campaign=php_repo_readme).

**What does the `solve` method return?**
The `solution` object of the server response as an associative array. Errors are thrown as exceptions that extend `CapMonsterException`, for example `GetResultException` with the API `errorCode`, or `TimeoutException`.

---

## ⚖️ Responsible Use

CapMonster Cloud is intended for legitimate business purposes only, such as testing your own website or a website you are authorized to test, and recognizing images you provide. Read more about [ethical and transparent captcha automation](https://capmonster.cloud/en/?utm_source=github&utm_medium=referral&utm_campaign=php_repo_readme).

---

## 📚 Documentation & Support

- 📖 [Getting Started Guide](https://docs.capmonster.cloud/docs/getting-start/?utm_source=github&utm_medium=referral&utm_campaign=php_repo_readme)
- 🎯 [Supported Captchas Overview](https://docs.capmonster.cloud/docs/captchas/?utm_source=github&utm_medium=referral&utm_campaign=php_repo_readme)
- ⚙️ [API Methods Reference](https://docs.capmonster.cloud/docs/methods/?utm_source=github&utm_medium=referral&utm_campaign=php_repo_readme)
- ❓ [CapMonster Cloud FAQ](https://docs.capmonster.cloud/docs/faq/?utm_source=github&utm_medium=referral&utm_campaign=php_repo_readme)
- 🌐 [Browser Extension Guides](https://docs.capmonster.cloud/docs/extension/?utm_source=github&utm_medium=referral&utm_campaign=php_repo_readme)
- 💬 [Support & Community](https://capmonster.cloud/en/?utm_source=github&utm_medium=referral&utm_campaign=php_repo_readme#support)

## Other official SDKs & integrations

- [Python](https://github.com/CapMonsterCloud/capmonster-python-captcha-solver)
- [Node.js](https://github.com/CapMonsterCloud/capmonster-nodejs-captcha-solver)
- [.NET](https://github.com/CapMonsterCloud/capmonster-dotnet-captcha-solver)
- [Go](https://github.com/CapMonsterCloud/capmonster-go-captcha-solver)
- [Rust](https://github.com/CapMonsterCloud/capmonster-rust-captcha-solver)
- [n8n](https://github.com/CapMonsterCloud/capmonster-n8n-captcha-solver)
- [MCP server](https://github.com/CapMonsterCloud/capmonster-mcp-captcha-solver)
- [MCP server for Patchright](https://github.com/CapMonsterCloud/capmonster-mcp-patchright-captcha-solver)
- [API Docs](https://github.com/CapMonsterCloud/capmonster-captcha-solver-docs)

---

## 📄 License

[MIT](./LICENSE) © [CapMonster Cloud](https://capmonster.cloud/en/?utm_source=github&utm_medium=referral&utm_campaign=php_repo_readme)
