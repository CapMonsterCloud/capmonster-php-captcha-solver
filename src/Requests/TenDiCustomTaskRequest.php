<?php

declare(strict_types=1);

namespace CapMonsterCloud\Requests;

use CapMonsterCloud\GetResultTimeouts;
use CapMonsterCloud\ProxyInfo;

/** TenDI / Tencent captcha (`CustomTask`, class `TenDI`). */
final class TenDiCustomTaskRequest extends AbstractRequest
{
    protected const TASK_TYPE = 'CustomTask';
    protected const TASK_CLASS = 'TenDI';

    /**
     * @param string      $websiteKey   `captchaAppId`, the `aid` value (e.g. `189123456`), unique per website.
     * @param string|null $captchaUrl   Link to the captcha script, usually `TCaptcha.js` / `TCaptcha-global.js`
     *                                  (`TJNCaptcha*.js` / `TJCaptcha*.js` for the second version).
     * @param string|null $aidEncrypted `Aid` value from the target website; single-use, fetch a new one for every task.
     *                                  Omit it if the site does not return an Aid.
     */
    public function __construct(
        public readonly string $websiteUrl,
        public readonly string $websiteKey,
        public readonly ?ProxyInfo $proxy = null,
        public readonly ?string $captchaUrl = null,
        public readonly ?string $aidEncrypted = null,
        public readonly ?string $userAgent = null,
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
        $metadata = self::withoutNulls(['captchaUrl' => $this->captchaUrl, 'aidEncrypted' => $this->aidEncrypted]);

        return [
            'websiteURL' => $this->websiteUrl,
            'websiteKey' => $this->websiteKey,
            'metadata' => $metadata === [] ? null : $metadata,
            'userAgent' => $this->userAgent,
        ] + self::proxyFields($this->proxy);
    }
}
