<?php

declare(strict_types=1);

namespace Zennolab\CapMonsterCloud\Requests;

use Zennolab\CapMonsterCloud\GetResultTimeouts;
use Zennolab\CapMonsterCloud\ProxyInfo;

/** reCAPTCHA v2 (`RecaptchaV2Task`). Without a proxy the task is solved from CapMonster's own IPs. */
final class RecaptchaV2Request extends AbstractRequest
{
    protected const TASK_TYPE = 'RecaptchaV2Task';

    /**
     * @param string      $websiteUrl  URL of the page with the captcha.
     * @param string      $websiteKey  reCAPTCHA site key.
     * @param string|null $dataSValue  One-time `data-s` token of some custom reCAPTCHA v2 implementations.
     * @param string|null $userAgent   Current browser User-Agent (Windows).
     * @param string|null $cookies     Cookies as `"name1=value1; name2=value2"`.
     */
    public function __construct(
        public readonly string $websiteUrl,
        public readonly string $websiteKey,
        public readonly ?ProxyInfo $proxy = null,
        public readonly ?string $dataSValue = null,
        public readonly ?string $userAgent = null,
        public readonly ?string $cookies = null,
        public readonly ?bool $isInvisible = null,
    ) {
    }

    public function timeouts(): GetResultTimeouts
    {
        return new GetResultTimeouts(1, 3, 180);
    }

    protected function validate(): void
    {
        self::requireNonEmpty('websiteURL', $this->websiteUrl);
        self::requireNonEmpty('websiteKey', $this->websiteKey);
    }

    protected function fields(): array
    {
        return [
            'websiteURL' => $this->websiteUrl,
            'websiteKey' => $this->websiteKey,
            'recaptchaDataSValue' => $this->dataSValue,
            'userAgent' => $this->userAgent,
            'cookies' => $this->cookies,
            'isInvisible' => $this->isInvisible,
        ] + self::proxyFields($this->proxy);
    }
}
