<?php

declare(strict_types=1);

namespace CapMonsterCloud\Requests;

use CapMonsterCloud\GetResultTimeouts;
use CapMonsterCloud\ProxyInfo;

/** Altcha proof-of-work captcha (`CustomTask`, class `altcha`). */
final class AltchaCustomTaskRequest extends AbstractRequest
{
    protected const TASK_TYPE = 'CustomTask';
    protected const TASK_CLASS = 'altcha';

    /**
     * @param string $websiteKey Site key; an empty string is allowed.
     * @param string $challenge  Challenge parameters taken from the page (`metadata`), all four are required.
     */
    public function __construct(
        public readonly string $websiteUrl,
        public readonly string $websiteKey,
        public readonly string $challenge,
        public readonly string $iterations,
        public readonly string $salt,
        public readonly string $signature,
        public readonly ?ProxyInfo $proxy = null,
        public readonly ?string $userAgent = null,
    ) {
    }

    public function timeouts(): GetResultTimeouts
    {
        return new GetResultTimeouts(1, 1, 50);
    }

    protected function validate(): void
    {
        self::requireNonEmpty('websiteURL', $this->websiteUrl);
        self::requireNonEmpty('metadata.challenge', $this->challenge);
        self::requireNonEmpty('metadata.iterations', $this->iterations);
        self::requireNonEmpty('metadata.salt', $this->salt);
        self::requireNonEmpty('metadata.signature', $this->signature);
    }

    protected function fields(): array
    {
        return [
            'websiteURL' => $this->websiteUrl,
            'websiteKey' => $this->websiteKey,
            'metadata' => [
                'challenge' => $this->challenge,
                'iterations' => $this->iterations,
                'salt' => $this->salt,
                'signature' => $this->signature,
            ],
            'userAgent' => $this->userAgent,
        ] + self::proxyFields($this->proxy);
    }
}
