<?php

declare(strict_types=1);

namespace CapMonsterCloud\Requests;

use CapMonsterCloud\GetResultTimeouts;
use CapMonsterCloud\ProxyInfo;

/** Imperva / Incapsula (`CustomTask`, class `Imperva`). Your own proxy is required. */
final class ImpervaCustomTaskRequest extends AbstractRequest
{
    protected const TASK_TYPE = 'CustomTask';
    protected const TASK_CLASS = 'Imperva';

    /**
     * Metadata values are dynamic: extract them right before creating the task.
     *
     * @param string      $incapsulaScriptUrl Name of the Incapsula JS file, e.g. `_Incapsula_Resource?SWJIYLWA=719d34d3...`.
     * @param string      $incapsulaCookies   Incapsula cookies, e.g. `"incap_sess_*=...; visid_incap_*=..."`.
     * @param string|null $reese84UrlEndpoint Name of the endpoint the reese84 fingerprint is sent to (ends with `?d=site.com`).
     */
    public function __construct(
        public readonly string $websiteUrl,
        public readonly string $incapsulaScriptUrl,
        public readonly string $incapsulaCookies,
        public readonly ProxyInfo $proxy,
        public readonly ?string $reese84UrlEndpoint = null,
        public readonly ?string $userAgent = null,
    ) {
    }

    public function timeouts(): GetResultTimeouts
    {
        return new GetResultTimeouts(1, 1, 15);
    }

    protected function validate(): void
    {
        self::requireNonEmpty('websiteURL', $this->websiteUrl);
        self::requireNonEmpty('metadata.incapsulaScriptUrl', $this->incapsulaScriptUrl);
        self::requireNonEmpty('metadata.incapsulaCookies', $this->incapsulaCookies);
    }

    protected function fields(): array
    {
        return [
            'websiteURL' => $this->websiteUrl,
            'metadata' => self::withoutNulls([
                'incapsulaScriptUrl' => $this->incapsulaScriptUrl,
                'incapsulaCookies' => $this->incapsulaCookies,
                'reese84UrlEndpoint' => $this->reese84UrlEndpoint,
            ]),
            'userAgent' => $this->userAgent,
        ] + self::proxyFields($this->proxy);
    }
}
