<?php

declare(strict_types=1);

namespace CapMonsterCloud\Requests;

use CapMonsterCloud\GetResultTimeouts;

/** ARCaptcha (`ArCaptchaTask`). The token is in `solution.token`. */
final class ArCaptchaRequest extends AbstractRequest
{
    protected const TASK_TYPE = 'ArCaptchaTask';

    /**
     * @param string      $websiteKey  The `siteKey` value found on the page (`site_key` of the widget).
     * @param bool|null   $isInvisible `true` if the captcha is invisible (has a hidden verification field).
     * @param string|null $userAgent   Browser User-Agent.
     */
    public function __construct(
        public readonly string $websiteUrl,
        public readonly string $websiteKey,
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
            'isInvisible' => $this->isInvisible,
            'userAgent' => $this->userAgent,
        ];
    }
}
