<?php

declare(strict_types=1);

namespace Zennolab\CapMonsterCloud\Requests;

use Zennolab\CapMonsterCloud\GetResultTimeouts;
use Zennolab\CapMonsterCloud\ProxyInfo;

/**
 * DataDome (`CustomTask`, class `DataDome`). Your own proxy is required; if it uses IP authorization,
 * whitelist `65.21.190.34`. The solution contains cookies that must be set in the browser.
 */
final class DataDomeCustomTaskRequest extends AbstractRequest
{
    protected const TASK_TYPE = 'CustomTask';
    protected const TASK_CLASS = 'DataDome';

    /** `datadomeVersion` value of the updated solving method (supports both `i.js` and `c.js`). */
    public const VERSION_NEW = 'new';

    /**
     * Metadata values are dynamic: extract them right before creating the task.
     *
     * @param string      $captchaUrl      CAPTCHA URL with the latest dynamic parameters, usually
     *                                     `https://geo.captcha-delivery.com/captcha/?initialCid=...&t=fe&...`.
     *                                     Submit it only if its `t` parameter is `fe` (`t=bv` means the proxy is blocked).
     * @param string      $datadomeCookie  Your DataDome cookies (`datadome=...`).
     * @param string|null $datadomeVersion {@see VERSION_NEW}; when absent the legacy method (only `i.js`) is used.
     */
    public function __construct(
        public readonly string $websiteUrl,
        public readonly string $captchaUrl,
        public readonly string $datadomeCookie,
        public readonly ProxyInfo $proxy,
        public readonly ?string $datadomeVersion = null,
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
        self::requireNonEmpty('metadata.captchaUrl', $this->captchaUrl);
        self::requireNonEmpty('metadata.datadomeCookie', $this->datadomeCookie);
    }

    protected function fields(): array
    {
        return [
            'websiteURL' => $this->websiteUrl,
            'metadata' => self::withoutNulls([
                'captchaUrl' => $this->captchaUrl,
                'datadomeCookie' => $this->datadomeCookie,
                'datadomeVersion' => $this->datadomeVersion,
            ]),
            'userAgent' => $this->userAgent,
        ] + self::proxyFields($this->proxy);
    }
}
