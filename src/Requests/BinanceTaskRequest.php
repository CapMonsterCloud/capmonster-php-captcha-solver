<?php

declare(strict_types=1);

namespace Zennolab\CapMonsterCloud\Requests;

use Zennolab\CapMonsterCloud\GetResultTimeouts;
use Zennolab\CapMonsterCloud\ProxyInfo;

/**
 * Binance captcha (`BinanceTask`). Use only to log in with your own account.
 *
 * `validateId` is dynamic: extract it right before creating the task.
 */
final class BinanceTaskRequest extends AbstractRequest
{
    protected const TASK_TYPE = 'BinanceTask';

    /**
     * @param string $websiteKey Section of the site: value of `bizId`, `bizType` or `bizCode` from the traffic, e.g. `login`.
     * @param string $validateId Value of `validateId`, `securityId` or `securityCheckResponseValidateId` from the traffic.
     */
    public function __construct(
        public readonly string $websiteUrl,
        public readonly string $websiteKey,
        public readonly string $validateId,
        public readonly ?ProxyInfo $proxy = null,
        public readonly ?string $userAgent = null,
    ) {
    }

    public function timeouts(): GetResultTimeouts
    {
        return new GetResultTimeouts(1, 1, 20);
    }

    protected function validate(): void
    {
        self::requireNonEmpty('websiteURL', $this->websiteUrl);
        self::requireNonEmpty('websiteKey', $this->websiteKey);
        self::requireNonEmpty('validateId', $this->validateId);
    }

    protected function fields(): array
    {
        return [
            'websiteURL' => $this->websiteUrl,
            'websiteKey' => $this->websiteKey,
            'validateId' => $this->validateId,
            'userAgent' => $this->userAgent,
        ] + self::proxyFields($this->proxy);
    }
}
