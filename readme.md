# CapMonster Cloud PHP SDK: PHP Captcha Solver for reCAPTCHA, Cloudflare Turnstile & GeeTest

<p align="center">
  <a href="https://capmonster.cloud/en/?utm_source=github&utm_medium=referral&utm_campaign=php_repo_readme">
    <img src="https://img.shields.io/badge/CapMonster%20Cloud-PHP%20Captcha%20Solver-00B2FF?style=for-the-badge&logo=php&logoColor=white" alt="CapMonster Cloud PHP SDK - captcha solver for PHP" height="40">
  </a>
</p>

PHP client library for the [CapMonster Cloud](https://capmonster.cloud/en/?utm_source=github&utm_medium=referral&utm_campaign=php_repo_readme) (Zennolab) captcha solving API. Use this PHP captcha solver to solve (bypass) reCAPTCHA v2, reCAPTCHA v3, reCAPTCHA v2 Enterprise, Cloudflare Turnstile, GeeTest, reCAPTCHA image-click and text (image-to-text) captchas in web scraping, browser automation and backend scripts: send a request class to the `solve` method and get a ready solution.

To get your API key, follow the [Getting Started Guide](https://docs.capmonster.cloud/docs/getting-start/?utm_source=github&utm_medium=referral&utm_campaign=php_repo_readme): register on CapMonster Cloud, top up your balance and use your personal API key in the client.

---

## Key Features

- **One call to solve a captcha:** create a request object and pass it to `$client->solve()`.
- **Ready-made request classes** for reCAPTCHA v2, v2 Enterprise and v3, Cloudflare Turnstile, GeeTest, image-to-text and reCAPTCHA image-click captchas, with and without a proxy where supported.
- **Unified response:** every call returns the same structure, so success and error handling stays simple.
- **Backed by the official API:** request types follow the [CapMonster Cloud API documentation](https://docs.capmonster.cloud/docs/captchas/?utm_source=github&utm_medium=referral&utm_campaign=php_repo_readme).

---

## Usage

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

## Supported captchas

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

**Which captcha types does the PHP SDK solve?**
See the table above. You can also browse the [Supported Captchas Overview](https://docs.capmonster.cloud/docs/captchas/?utm_source=github&utm_medium=referral&utm_campaign=php_repo_readme) for all task types of the service.

**How do I get an API key?**
Register on CapMonster Cloud, top up your balance and copy your personal API key. The [Getting Started Guide](https://docs.capmonster.cloud/docs/getting-start/?utm_source=github&utm_medium=referral&utm_campaign=php_repo_readme) describes each step.

**What does the `solve` method return?**
A result with two fields: a boolean `result` (request success indicator) and a `message` that holds either a text description of the error or the object of a successful server response.

**How fast are captchas solved?**
On average between 1 and 30 seconds, depending on the captcha type. More details are in the [CapMonster Cloud FAQ](https://docs.capmonster.cloud/docs/faq/?utm_source=github&utm_medium=referral&utm_campaign=php_repo_readme).

**How long is a reCAPTCHA v2 solution valid?**
According to the [reCAPTCHA v2 documentation](https://docs.capmonster.cloud/docs/captchas/no-captcha-task/?utm_source=github&utm_medium=referral&utm_campaign=php_repo_readme), the returned token is valid for 60 seconds after the captcha is solved, so use it right away.

**Where is the full API reference?**
See the [API Methods Reference](https://docs.capmonster.cloud/docs/api/methods/create-task/?utm_source=github&utm_medium=referral&utm_campaign=php_repo_readme).

---

## Responsible Use

CapMonster Cloud is intended for legitimate business purposes only, such as testing your own website or a website you are authorized to test, and recognizing images you provide. Read more about [ethical and transparent captcha automation](https://capmonster.cloud/en/?utm_source=github&utm_medium=referral&utm_campaign=php_repo_readme).

---

## Documentation & Links

- [Getting Started Guide](https://docs.capmonster.cloud/docs/getting-start/?utm_source=github&utm_medium=referral&utm_campaign=php_repo_readme)
- [Supported Captchas Overview](https://docs.capmonster.cloud/docs/captchas/?utm_source=github&utm_medium=referral&utm_campaign=php_repo_readme)
- [API Methods Reference](https://docs.capmonster.cloud/docs/api/methods/create-task/?utm_source=github&utm_medium=referral&utm_campaign=php_repo_readme)
- [CapMonster Cloud FAQ](https://docs.capmonster.cloud/docs/faq/?utm_source=github&utm_medium=referral&utm_campaign=php_repo_readme)
- [CapMonster Cloud website](https://capmonster.cloud/en/?utm_source=github&utm_medium=referral&utm_campaign=php_repo_readme)

---

## License

See the [LICENSE](./LICENSE) file.
