<?php

declare(strict_types=1);

namespace CapMonsterCloud\Requests;

use CapMonsterCloud\Exception\ValidationException;
use CapMonsterCloud\GetResultTimeouts;
use CapMonsterCloud\ProxyInfo;

/**
 * reCAPTCHA v2 Enterprise (`RecaptchaV2EnterpriseTask`).
 *
 * Without a proxy the task is solved from CapMonster's own IPs; a proxy is only needed when the website
 * does not accept the token.
 */
final class RecaptchaV2EnterpriseRequest extends AbstractRequest
{
    protected const TASK_TYPE = 'RecaptchaV2EnterpriseTask';

    /**
     * @param array<string, string>|null $enterprisePayload Additional Enterprise parameters, e.g. `['s' => '...']`
     *                                                      (the `s` value passed to `grecaptcha.enterprise.render`).
     * @param string|null $apiDomain  Domain to load reCAPTCHA Enterprise from: `www.google.com` or `www.recaptcha.net`.
     * @param string|null $pageAction Value of the `action` parameter, if it differs from the default `verify`.
     * @param string|null $cookies    Cookies as `"name1=value1; name2=value2"`.
     */
    public function __construct(
        public readonly string $websiteUrl,
        public readonly string $websiteKey,
        public readonly ?ProxyInfo $proxy = null,
        public readonly ?array $enterprisePayload = null,
        public readonly ?string $apiDomain = null,
        public readonly ?string $pageAction = null,
        public readonly ?string $userAgent = null,
        public readonly ?string $cookies = null,
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
        if ($this->enterprisePayload === []) {
            throw new ValidationException('enterprisePayload must not be an empty array');
        }
    }

    protected function fields(): array
    {
        return [
            'websiteURL' => $this->websiteUrl,
            'websiteKey' => $this->websiteKey,
            'enterprisePayload' => $this->enterprisePayload,
            'apiDomain' => $this->apiDomain,
            'pageAction' => $this->pageAction,
            'userAgent' => $this->userAgent,
            'cookies' => $this->cookies,
        ] + self::proxyFields($this->proxy);
    }
}
