# CapMonster Cloud PHP SDK: PHP Captcha Solver for reCAPTCHA, Cloudflare Turnstile & GeeTest

> **🔄 Migrating from 2Captcha or Capsolver?** CapMonster Cloud is a fast, cost-effective **2captcha alternative** and **capsolver alternative** for PHP: reCAPTCHA v3 from **$0.90 per 1,000 solves**, which is **up to 38% cheaper than 2Captcha** and **10% cheaper than Capsolver**, with an API response time of **≤ 2 s**. Already have software built for 2Captcha? CapMonster Cloud [supports the 2Captcha API format](https://docs.capmonster.cloud/docs/external-services/?utm_source=github&utm_medium=referral&utm_campaign=php_repo_readme). Moving your PHP code takes just a few lines: see the **Migration examples** below. Full comparison and disclaimer are in the comparison section below.

<p align="center">
  <a href="https://capmonster.cloud/en/?utm_source=github&utm_medium=referral&utm_campaign=php_repo_readme">
    <img src="https://img.shields.io/badge/CapMonster%20Cloud-PHP%20Captcha%20Solver-00B2FF?style=for-the-badge&logo=php&logoColor=white" alt="CapMonster Cloud PHP SDK - captcha solver for PHP" height="40">
  </a>
</p>

PHP client library for the [CapMonster Cloud](https://capmonster.cloud/en/?utm_source=github&utm_medium=referral&utm_campaign=php_repo_readme) captcha solving API. Use this PHP captcha solver for captcha bypass: bypass reCAPTCHA v2, reCAPTCHA v3, reCAPTCHA v2 Enterprise and Cloudflare Turnstile, solve GeeTest, reCAPTCHA image-click and text (image-to-text) captchas in web scraping, browser automation and backend scripts: send a request class to the `solve` method and get a ready solution. As the official CapMonster SDK for PHP, it is a cost-effective 2captcha alternative and capsolver alternative.

To get your API key, follow the [Getting Started Guide](https://docs.capmonster.cloud/docs/getting-start/?utm_source=github&utm_medium=referral&utm_campaign=php_repo_readme): register on CapMonster Cloud, top up your balance and use your personal API key in the client.

---

## Key Features

- **Captcha bypass in one call:** create a request object and pass it to `$client->solve()`.
- **reCAPTCHA bypass and Cloudflare Turnstile bypass:** ready-made request classes for reCAPTCHA v2, v2 Enterprise and v3, Cloudflare Turnstile, GeeTest, image-to-text and reCAPTCHA image-click captchas, with and without a proxy where supported.
- **Unified response:** every call returns the same structure, so success and error handling stays simple.
- **Cheaper than alternatives:** reCAPTCHA v3 from $0.90 per 1,000 solves, versus $1.45 at 2Captcha and $1.00 at Capsolver.
- **Easy migration:** replace your 2Captcha or Capsolver solver call and keep the rest of your scraper or bot unchanged.
- **Backed by the official API:** request types follow the [CapMonster Cloud API documentation](https://docs.capmonster.cloud/docs/captchas/?utm_source=github&utm_medium=referral&utm_campaign=php_repo_readme).

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

## Usage: Solve and Bypass Captchas in PHP

```php
    include './client/Client.php';
    include './client/src/captcha/ImageToText.php';
    include './client/src/captcha/RecaptchaV2.php';
    include './client/src/captcha/HCaptcha.php';
    
    $client = new Client("your_client_key");

    //solve image captcha
    $body = "base64_captcha_image";
    $imageRequest = new ImageToTextRequest($body);
    $imageResult = $client->solve($imageRequest);
    
    //solve Recaptcha 2 (without proxy)
    $websiteURL = "[https://lessons.zennolab.com/captchas/recaptcha/v2_simple.php?level=high](https://lessons.zennolab.com/captchas/recaptcha/v2_simple.php?level=high)";
    $websiteKey = "6Lcg7CMUAAAAANphynKgn9YAgA4tQ2KI_iqRyTwd";
    $recaptchaV2Request = new RecaptchaV2Request($websiteURL, $websiteKey);
    $recaptchaV2Result = $client->solve($recaptchaV2Request);
    
    // solve HCaptcha (without proxy)
    $websiteUrl = "[https://lessons.zennolab.com/captchas/hcaptcha/?level=easy](https://lessons.zennolab.com/captchas/hcaptcha/?level=easy)";
    $websiteKey = "472fc7af-86a4-4382-9a49-ca9090474471";
    $hcatpchaRequest = new HCaptchaRequest($websiteURL, $websiteKey);
    $hcaptchaResult = $client->solve($hcatpchaRequest);
```

---

## Response format

 The result of the solve method always contains two fields: bool result, a request success indicator, and a mixed message field containing a text description of the error or an object of a successful response from the server.

---

## 🔄 Migrating from 2Captcha or Capsolver

Already use 2Captcha or Capsolver in your PHP scraper or bot? Replace only the solver call and keep the rest of your code unchanged. CapMonster Cloud follows the same flow: send the website URL and sitekey, receive the token.

### Migration from 2Captcha (2captcha-php)

```php
// Before: 2Captcha
$solver = new \TwoCaptcha\TwoCaptcha('YOUR_2CAPTCHA_API_KEY');
$result = $solver->recaptcha([
    'sitekey' => 'YOUR_SITE_KEY',
    'url'     => '[https://target-website.com/page-with-recaptcha](https://target-website.com/page-with-recaptcha)',
]);
$token = $result->code;
```

### After: CapMonster Cloud

```php
<?php
include './client/Client.php';
include './client/src/captcha/RecaptchaV2.php';

$client = new Client("YOUR_CAPMONSTER_API_KEY");

$request = new RecaptchaV2Request(
    "[https://target-website.com/page-with-recaptcha](https://target-website.com/page-with-recaptcha)",
    "YOUR_SITE_KEY"
);
$result = $client->solve($request);

if ($result->result) {
    $token = $result->message['gRecaptchaResponse'];
    echo $token;
} else {
    echo $result->message;
}
```

Only the client, the API key and the request class change: the token is used exactly as before, so the rest of your scraper stays the same.

### Migrating from Capsolver

CapMonster Cloud uses the same `createTask` / `getTaskResult` flow, so if you call the Capsolver HTTP API directly from PHP, you can switch to the CapMonster Cloud PHP SDK above or follow the [API Methods Reference](https://docs.capmonster.cloud/docs/api/methods/create-task/?utm_source=github&utm_medium=referral&utm_campaign=php_repo_readme).

### Keep existing 2Captcha-based software

Software that already speaks the 2Captcha API can work with CapMonster Cloud without code changes. See the [guide for connecting other services' APIs](https://docs.capmonster.cloud/docs/external-services/?utm_source=github&utm_medium=referral&utm_campaign=php_repo_readme).

---

## Supported captchas: Bypass reCAPTCHA, Cloudflare Turnstile, GeeTest and More

Use this PHP captcha solver for captcha bypass in web scraping and automation: reCAPTCHA bypass (v2, v2 Enterprise, v3), Cloudflare Turnstile solver, GeeTest solver, FunCaptcha solving and image-to-text recognition.

Every request class below follows the official [CapMonster Cloud API documentation](https://docs.capmonster.cloud/docs/captchas/?utm_source=github&utm_medium=referral&utm_campaign=php_repo_readme):

| Captcha type | PHP request classes | Documentation |
| :--- | :--- | :--- |
| reCAPTCHA v2 | `RecaptchaV2ProxylessRequest`, `RecaptchaV2Request` | [RecaptchaV2Task](https://docs.capmonster.cloud/docs/captchas/no-captcha-task/?utm_source=github&utm_medium=referral&utm_campaign=php_repo_readme) |
| reCAPTCHA v2 Enterprise | `RecaptchaV2EnterpriseProxylessRequest`, `RecaptchaV2EnterpriseRequest` | [RecaptchaV2EnterpriseTask](https://docs.capmonster.cloud/docs/captchas/recaptcha-v2-enterprise-task/?utm_source=github&utm_medium=referral&utm_campaign=php_repo_readme) |
| reCAPTCHA v3 | `RecaptchaV3ProxylessRequest` | [RecaptchaV3TaskProxyless](https://docs.capmonster.cloud/docs/captchas/recaptcha-v3-task/?utm_source=github&utm_medium=referral&utm_campaign=php_repo_readme) |
| reCAPTCHA image-click (ComplexImageTask) | `RecaptchaComplexImageTaskRequest` | [ComplexImageTask reCAPTCHA](https://docs.capmonster.cloud/docs/captchas/recaptcha-click/?utm_source=github&utm_medium=referral&utm_campaign=php_repo_readme) |
| Cloudflare Turnstile | `TurnstileProxylessRequest`, `TurnstileRequest` | [TurnstileTask](https://docs.capmonster.cloud/docs/captchas/turnstile-task/?utm_source=github&utm_medium=referral&utm_campaign=php_repo_readme) |
| GeeTest | `GeeTestProxylessRequest`, `GeeTestRequest` | [GeeTestTask](https://docs.capmonster.cloud/docs/captchas/geetest-task/?utm_source=github&utm_medium=referral&utm_campaign=php_repo_readme) |
| Text captcha (image to text) | `ImageToTextRequest` | [ImageToTextTask](https://docs.capmonster.cloud/docs/captchas/image-to-text/?utm_source=github&utm_medium=referral&utm_campaign=php_repo_readme) |

---

## FAQ

**Is CapMonster Cloud a 2Captcha alternative for PHP?**
Yes. It is a captcha solving service with an official PHP SDK. For reCAPTCHA v3 it starts at $0.90 per 1,000 solves versus $1.45 at 2Captcha, which is up to 38% cheaper. For Cloudflare Turnstile it starts at $1.30 versus $1.45, which is 10% cheaper.

**Is CapMonster Cloud a Capsolver alternative for PHP?**
Yes. For reCAPTCHA v3 it is 10% cheaper than Capsolver ($0.90 versus $1.00 per 1,000 solves) and shows an API response time of ≤ 2 s versus < 3 s. See the comparison table above and its disclaimer.

**How do I migrate from 2captcha-php to CapMonster Cloud?**
Create a `Client` with your CapMonster Cloud API key, build the matching request class and call `$client->solve()`. See the migration examples above. Software that already supports the 2Captcha API can also be connected through the [CapMonster Cloud guide](https://docs.capmonster.cloud/docs/external-services/?utm_source=github&utm_medium=referral&utm_campaign=php_repo_readme).

**How do I bypass reCAPTCHA v2 in PHP?**
Create a `Client` with your API key, build a `RecaptchaV2Request` with the page URL and sitekey, call `$client->solve($request)` and read the token from `$result->message['gRecaptchaResponse']`. See the [reCAPTCHA v2 documentation](https://docs.capmonster.cloud/docs/captchas/no-captcha-task/?utm_source=github&utm_medium=referral&utm_campaign=php_repo_readme).

**How do I bypass Cloudflare Turnstile in PHP?**
Use `TurnstileProxylessRequest` or `TurnstileRequest` from the table above. According to the [Turnstile documentation](https://docs.capmonster.cloud/docs/captchas/turnstile-task/?utm_source=github&utm_medium=referral&utm_campaign=php_repo_readme), all Turnstile subtypes are supported automatically: manual, non-interactive and invisible.

**Which captcha types does the PHP SDK solve?**
See the table above. You can also browse the [Supported Captchas Overview](https://docs.capmonster.cloud/docs/captchas/?utm_source=github&utm_medium=referral&utm_campaign=php_repo_readme) for all task types of the service
