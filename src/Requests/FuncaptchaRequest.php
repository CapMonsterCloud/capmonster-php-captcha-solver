<?php

declare(strict_types=1);

namespace CapMonsterCloud\Requests;

use CapMonsterCloud\GetResultTimeouts;
use CapMonsterCloud\ProxyInfo;

/**
 * FunCaptcha / Arkose Labs (`FunCaptchaTask`).
 *
 * Your own proxy is required: use the same proxy to obtain the captcha parameters and to create the task.
 */
final class FuncaptchaRequest extends AbstractRequest
{
    protected const TASK_TYPE = 'FunCaptchaTask';

    /**
     * @param string      $websitePublicKey         FunCaptcha public key (`pk`), e.g. `EX72CCFB-26EX-40E5-91E6-85EX70BE98ED`.
     * @param string|null $funcaptchaApiJsSubdomain Arkose Labs subdomain (`surl`) without `https://`, only if it
     *                                              differs from `client-api.arkoselabs.com`.
     * @param string|null $data                     Required if the site uses `data[blob]`, e.g. `{"blob":"..."}`.
     * @param string|null $cookies                  Cookies as `"name1=value1; name2=value2"`.
     */
    public function __construct(
        public readonly string $websiteUrl,
        public readonly string $websitePublicKey,
        public readonly ProxyInfo $proxy,
        public readonly ?string $funcaptchaApiJsSubdomain = null,
        public readonly ?string $data = null,
        public readonly ?string $cookies = null,
        public readonly ?string $userAgent = null,
    ) {
    }

    public function timeouts(): GetResultTimeouts
    {
        return new GetResultTimeouts(1, 1, 80);
    }

    protected function validate(): void
    {
        self::requireNonEmpty('websiteURL', $this->websiteUrl);
        self::requireNonEmpty('websitePublicKey', $this->websitePublicKey);
    }

    protected function fields(): array
    {
        return [
            'websiteURL' => $this->websiteUrl,
            'websitePublicKey' => $this->websitePublicKey,
            'funcaptchaApiJSSubdomain' => $this->funcaptchaApiJsSubdomain,
            'data' => $this->data,
            'cookies' => $this->cookies,
            'userAgent' => $this->userAgent,
        ] + self::proxyFields($this->proxy);
    }
}
