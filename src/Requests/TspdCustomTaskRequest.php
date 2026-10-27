<?php

declare(strict_types=1);

namespace CapMonsterCloud\Requests;

use CapMonsterCloud\Exception\ValidationException;
use CapMonsterCloud\GetResultTimeouts;
use CapMonsterCloud\ProxyInfo;

/** TSPD (`CustomTask`, class `tspd`). Your own proxy is required; its login must not contain `;`, `|`, `)`. */
final class TspdCustomTaskRequest extends AbstractRequest
{
    protected const TASK_TYPE = 'CustomTask';
    protected const TASK_CLASS = 'tspd';

    /**
     * Metadata values are dynamic: extract them right before creating the task.
     *
     * @param string      $tspdCookie     Cookies obtained on the TSPD challenge page, e.g. `"TS386a400d029=0826...; ...;"`.
     * @param string      $htmlPageBase64 The entire TSPD page encoded in base64.
     * @param string|null $userAgent      Should match the one used when the cookie and the page were obtained.
     */
    public function __construct(
        public readonly string $websiteUrl,
        public readonly string $tspdCookie,
        public readonly string $htmlPageBase64,
        public readonly ProxyInfo $proxy,
        public readonly ?string $userAgent = null,
    ) {
    }

    public function timeouts(): GetResultTimeouts
    {
        return new GetResultTimeouts(1, 1, 60);
    }

    protected function validate(): void
    {
        self::requireNonEmpty('websiteURL', $this->websiteUrl);
        self::requireNonEmpty('metadata.tspdCookie', $this->tspdCookie);
        self::requireNonEmpty('metadata.htmlPageBase64', $this->htmlPageBase64);
        if ($this->proxy->login !== null && strpbrk($this->proxy->login, ';|)') !== false) {
            throw new ValidationException("proxy login must not contain the characters ';', '|', ')' for TSPD");
        }
    }

    protected function fields(): array
    {
        return [
            'websiteURL' => $this->websiteUrl,
            'metadata' => ['tspdCookie' => $this->tspdCookie, 'htmlPageBase64' => $this->htmlPageBase64],
            'userAgent' => $this->userAgent,
        ] + self::proxyFields($this->proxy);
    }
}
