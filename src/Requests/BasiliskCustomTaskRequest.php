<?php

declare(strict_types=1);

namespace CapMonsterCloud\Requests;

use CapMonsterCloud\GetResultTimeouts;
use CapMonsterCloud\ProxyInfo;

/** Basilisk captcha (`CustomTask`, class `Basilisk`). */
final class BasiliskCustomTaskRequest extends AbstractRequest
{
    protected const TASK_TYPE = 'CustomTask';
    protected const TASK_CLASS = 'Basilisk';

    /**
     * @param string $websiteKey The `data-sitekey` attribute of the captcha container, or the `site_key` field of
     *                           the POST payload to `https://basiliskcaptcha.com/challenge/check-site`.
     */
    public function __construct(
        public readonly string $websiteUrl,
        public readonly string $websiteKey,
        public readonly ?ProxyInfo $proxy = null,
        public readonly ?string $userAgent = null,
    ) {
    }

    public function timeouts(): GetResultTimeouts
    {
        return new GetResultTimeouts(1, 3, 100);
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
            'userAgent' => $this->userAgent,
        ] + self::proxyFields($this->proxy);
    }
}
