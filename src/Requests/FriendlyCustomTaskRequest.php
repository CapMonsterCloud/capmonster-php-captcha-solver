<?php

declare(strict_types=1);

namespace Zennolab\CapMonsterCloud\Requests;

use Zennolab\CapMonsterCloud\GetResultTimeouts;
use Zennolab\CapMonsterCloud\ProxyInfo;

/** Friendly Captcha (`CustomTask`, class `friendly`). */
final class FriendlyCustomTaskRequest extends AbstractRequest
{
    protected const TASK_TYPE = 'CustomTask';
    protected const TASK_CLASS = 'friendly';

    /**
     * @param string $websiteKey Friendly Captcha site key.
     * @param string $apiGetLib  URL of the JS file, used to detect the captcha version (`metadata.apiGetLib`).
     *                           V1: `https://cdn.jsdelivr.net/npm/friendly-challenge@X.Y.Z/widget.module.min.js`
     *                           (`X.Y.Z` from the `x-frc-client` header). V2: URL of the `site.min.js` loaded on the page,
     *                           e.g. `https://cdn.jsdelivr.net/npm/@friendlycaptcha/sdk@X.Y.Z/site.min.js`.
     */
    public function __construct(
        public readonly string $websiteUrl,
        public readonly string $websiteKey,
        public readonly string $apiGetLib,
        public readonly ?ProxyInfo $proxy = null,
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
        self::requireNonEmpty('metadata.apiGetLib', $this->apiGetLib);
    }

    protected function fields(): array
    {
        return [
            'websiteURL' => $this->websiteUrl,
            'websiteKey' => $this->websiteKey,
            'metadata' => ['apiGetLib' => $this->apiGetLib],
            'userAgent' => $this->userAgent,
        ] + self::proxyFields($this->proxy);
    }
}
