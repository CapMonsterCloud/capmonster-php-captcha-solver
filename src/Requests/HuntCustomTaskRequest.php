<?php

declare(strict_types=1);

namespace Zennolab\CapMonsterCloud\Requests;

use Zennolab\CapMonsterCloud\GetResultTimeouts;
use Zennolab\CapMonsterCloud\ProxyInfo;

/**
 * HUNT captcha (`CustomTask`, class `HUNT`). Your own proxy is required.
 *
 * Three modes, one factory each: {@see fingerprint()}, {@see byToken()}, {@see byWidgetUrl()}.
 * `apiGetLib` is the full URL of the `api.js` file (look for `hd-api` or `api.js` in the Network tab), e.g.
 * `https://example.com/hd-api/external/apps/<app-hash>/api.js`. Values are dynamic: extract them right before
 * creating the task.
 */
final class HuntCustomTaskRequest extends AbstractRequest
{
    protected const TASK_TYPE = 'CustomTask';
    protected const TASK_CLASS = 'HUNT';

    private function __construct(
        public readonly string $websiteUrl,
        public readonly string $apiGetLib,
        public readonly ProxyInfo $proxy,
        public readonly ?string $data,
        public readonly ?string $widgetUrl,
        public readonly ?string $userAgent,
        private readonly string $mode,
    ) {
    }

    /** Only generate a fingerprint (just `apiGetLib` is sent). */
    public static function fingerprint(string $websiteUrl, string $apiGetLib, ProxyInfo $proxy, ?string $userAgent = null): self
    {
        return new self($websiteUrl, $apiGetLib, $proxy, null, null, $userAgent, 'fingerprint');
    }

    /** Solve the captcha via the `meta.token` value received from the website (`metadata.data`). */
    public static function byToken(string $websiteUrl, string $apiGetLib, string $data, ProxyInfo $proxy, ?string $userAgent = null): self
    {
        return new self($websiteUrl, $apiGetLib, $proxy, $data, null, $userAgent, 'token');
    }

    /**
     * Solve the captcha via the full URL of the HUNT widget, including domain, path and query (`metadata.widgetUrl`),
     * e.g. `https://captcha.example.com/widget?hash=<widget-hash>`.
     */
    public static function byWidgetUrl(string $websiteUrl, string $apiGetLib, string $widgetUrl, ProxyInfo $proxy, ?string $userAgent = null): self
    {
        return new self($websiteUrl, $apiGetLib, $proxy, null, $widgetUrl, $userAgent, 'widget_url');
    }

    public function timeouts(): GetResultTimeouts
    {
        return new GetResultTimeouts(1, 3, 180);
    }

    protected function validate(): void
    {
        self::requireNonEmpty('websiteURL', $this->websiteUrl);
        self::requireNonEmpty('metadata.apiGetLib', $this->apiGetLib);
        if ($this->mode === 'token') {
            self::requireNonEmpty('metadata.data', $this->data);
        } elseif ($this->mode === 'widget_url') {
            self::requireNonEmpty('metadata.widgetUrl', $this->widgetUrl);
        }
    }

    protected function fields(): array
    {
        return [
            'websiteURL' => $this->websiteUrl,
            'metadata' => self::withoutNulls([
                'apiGetLib' => $this->apiGetLib,
                'data' => $this->data,
                'widgetUrl' => $this->widgetUrl,
            ]),
            'userAgent' => $this->userAgent,
        ] + self::proxyFields($this->proxy);
    }
}
