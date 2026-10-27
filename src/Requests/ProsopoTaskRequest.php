<?php

declare(strict_types=1);

namespace CapMonsterCloud\Requests;

use CapMonsterCloud\GetResultTimeouts;
use CapMonsterCloud\ProxyInfo;

/** Prosopo Procaptcha (`ProsopoTask`), token based. */
final class ProsopoTaskRequest extends AbstractRequest
{
    protected const TASK_TYPE = 'ProsopoTask';

    /**
     * @param string $websiteKey The `siteKey` found on the page, e.g. `5EZU3LG31uzq1Mwi8inwqxmfvFDpj7VzwDnZwj4Q3syyxBwV`.
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
        ] + self::proxyFields($this->proxy);
    }
}
