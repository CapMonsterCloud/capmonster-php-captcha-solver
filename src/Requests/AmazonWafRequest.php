<?php

declare(strict_types=1);

namespace CapMonsterCloud\Requests;

use CapMonsterCloud\GetResultTimeouts;
use CapMonsterCloud\ProxyInfo;

/**
 * Amazon AWS WAF captcha and challenge (`AmazonTask`).
 *
 * The API accepts three mutually exclusive parameter sets, one factory each: {@see visibleCaptcha()},
 * {@see challengeAndCaptcha()}, {@see challengeOnly()}.
 * `cookieSolution`: `false` (default) returns `captcha_voucher` and `existing_token`, `true` returns the
 * `aws-waf-token` cookie.
 */
final class AmazonWafRequest extends AbstractRequest
{
    protected const TASK_TYPE = 'AmazonTask';

    private const VISIBLE = 'visible';
    private const CHALLENGE_AND_CAPTCHA = 'challenge_and_captcha';
    private const CHALLENGE_ONLY = 'challenge_only';

    private function __construct(
        private readonly string $variant,
        public readonly string $websiteUrl,
        public readonly ?ProxyInfo $proxy,
        public readonly ?bool $cookieSolution,
        public readonly ?string $websiteKey = null,
        public readonly ?string $captchaScript = null,
        public readonly ?string $challengeScript = null,
        public readonly ?string $context = null,
        public readonly ?string $iv = null,
        public readonly ?string $userAgent = null,
    ) {
    }

    /**
     * Option 1: visible AWS WAF captcha (`jsapi.js` / `captcha.js` only).
     *
     * @param string      $websiteKey    Value of the `apiKey` field used when rendering the captcha.
     * @param string      $captchaScript Link to `jsapi.js` on the HTML page (`<Integration URL>jsapi.js`).
     * @param string|null $userAgent     Only this option accepts a User-Agent.
     */
    public static function visibleCaptcha(
        string $websiteUrl,
        string $websiteKey,
        string $captchaScript,
        ?ProxyInfo $proxy = null,
        ?string $userAgent = null,
        ?bool $cookieSolution = null,
    ): self {
        return new self(self::VISIBLE, $websiteUrl, $proxy, $cookieSolution, $websiteKey, $captchaScript, userAgent: $userAgent);
    }

    /**
     * Option 2: challenge plus captcha. The values come from `window.gokuProps` on the 405 page of the site.
     *
     * @param string      $challengeScript Link to `challenge.js`.
     * @param string      $websiteKey      `window.gokuProps.key`.
     * @param string      $context         `window.gokuProps.context`.
     * @param string      $iv              `window.gokuProps.iv`.
     * @param string|null $captchaScript   Link to `captcha.js`; may be absent if only a challenge is present.
     */
    public static function challengeAndCaptcha(
        string $websiteUrl,
        string $challengeScript,
        string $websiteKey,
        string $context,
        string $iv,
        ?string $captchaScript = null,
        ?ProxyInfo $proxy = null,
        ?bool $cookieSolution = null,
    ): self {
        return new self(self::CHALLENGE_AND_CAPTCHA, $websiteUrl, $proxy, $cookieSolution, $websiteKey, $captchaScript, $challengeScript, $context, $iv);
    }

    /**
     * Option 3: invisible captcha, only `challenge.js` is loaded. `context` and `iv` are sent as empty strings,
     * as the API requires.
     */
    public static function challengeOnly(
        string $websiteUrl,
        string $challengeScript,
        ?ProxyInfo $proxy = null,
        ?bool $cookieSolution = null,
    ): self {
        return new self(self::CHALLENGE_ONLY, $websiteUrl, $proxy, $cookieSolution, challengeScript: $challengeScript, context: '', iv: '');
    }

    public function timeouts(): GetResultTimeouts
    {
        return new GetResultTimeouts(1, 3, 180);
    }

    protected function validate(): void
    {
        self::requireNonEmpty('websiteURL', $this->websiteUrl);
        switch ($this->variant) {
            case self::VISIBLE:
                self::requireNonEmpty('websiteKey', $this->websiteKey);
                self::requireNonEmpty('captchaScript', $this->captchaScript);
                break;
            case self::CHALLENGE_AND_CAPTCHA:
                self::requireNonEmpty('challengeScript', $this->challengeScript);
                self::requireNonEmpty('websiteKey', $this->websiteKey);
                self::requireNonEmpty('context', $this->context);
                self::requireNonEmpty('iv', $this->iv);
                break;
            case self::CHALLENGE_ONLY:
                // `context` and `iv` are "" by design for this variant
                self::requireNonEmpty('challengeScript', $this->challengeScript);
                break;
        }
    }

    protected function fields(): array
    {
        return [
            'websiteURL' => $this->websiteUrl,
            'websiteKey' => $this->websiteKey,
            'captchaScript' => $this->captchaScript,
            'challengeScript' => $this->challengeScript,
            'context' => $this->context,
            'iv' => $this->iv,
            'userAgent' => $this->userAgent,
            'cookieSolution' => $this->cookieSolution,
        ] + self::proxyFields($this->proxy);
    }
}
