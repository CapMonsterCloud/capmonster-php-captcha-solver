<?php

declare(strict_types=1);

namespace CapMonsterCloud\Requests;

use CapMonsterCloud\GetResultTimeouts;
use CapMonsterCloud\ProxyInfo;

/** MTCaptcha (`MTCaptchaTask`). */
final class MtCaptchaRequest extends AbstractRequest
{
    protected const TASK_TYPE = 'MTCaptchaTask';

    /**
     * @param string      $websiteKey  MTCaptcha key, passed on the page as `sk`; typically `MTPublic-abCDEFJAB`.
     * @param string|null $pageAction  The `act` parameter. Send it only if it differs from the default `%24`.
     * @param bool|null   $isInvisible `true` if the captcha is invisible (has a hidden confirmation field).
     */
    public function __construct(
        public readonly string $websiteUrl,
        public readonly string $websiteKey,
        public readonly ?ProxyInfo $proxy = null,
        public readonly ?string $pageAction = null,
        public readonly ?bool $isInvisible = null,
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
            'pageAction' => $this->pageAction,
            'isInvisible' => $this->isInvisible,
            'userAgent' => $this->userAgent,
        ] + self::proxyFields($this->proxy);
    }
}
