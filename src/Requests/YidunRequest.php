<?php

declare(strict_types=1);

namespace CapMonsterCloud\Requests;

use CapMonsterCloud\GetResultTimeouts;
use CapMonsterCloud\ProxyInfo;

/** NetEase Yidun captcha (`YidunTask`). Setting `challenge` selects the Enterprise variant. */
final class YidunRequest extends AbstractRequest
{
    protected const TASK_TYPE = 'YidunTask';

    /**
     * @param string      $websiteKey              The `siteKey` value found on the page.
     * @param string|null $yidunGetLib             Full URL (`https://...`) of the JS file that loads the captcha.
     *                                             Recommended when `challenge`, `hcg` or `hct` are present on the site.
     * @param string|null $yidunApiServerSubdomain Yidun API server subdomain without `https://`; must differ from
     *                                             `c.dun.163.com` / `c-v6.dun.163.com`. Needed for custom (often Enterprise) servers.
     * @param string|null $challenge               Unique identifier of the current captcha; indicates the Enterprise captcha.
     * @param string|null $hcg                     Captcha hash used in the request; obtained together with `challenge`.
     * @param int|null    $hct                     Numeric timestamp used in Enterprise validation.
     */
    public function __construct(
        public readonly string $websiteUrl,
        public readonly string $websiteKey,
        public readonly ?ProxyInfo $proxy = null,
        public readonly ?string $userAgent = null,
        public readonly ?string $yidunGetLib = null,
        public readonly ?string $yidunApiServerSubdomain = null,
        public readonly ?string $challenge = null,
        public readonly ?string $hcg = null,
        public readonly ?int $hct = null,
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
            'userAgent' => $this->userAgent,
            'yidunGetLib' => $this->yidunGetLib,
            'yidunApiServerSubdomain' => $this->yidunApiServerSubdomain,
            'challenge' => $this->challenge,
            'hcg' => $this->hcg,
            'hct' => $this->hct,
        ] + self::proxyFields($this->proxy);
    }
}
