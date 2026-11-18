<?php

declare(strict_types=1);

namespace CapMonsterCloud\Requests;

use CapMonsterCloud\Exception\ValidationException;
use CapMonsterCloud\GetResultTimeouts;
use CapMonsterCloud\ProxyInfo;

/** Proton Captcha (`CustomTask`, class `proton`). Your own proxy is required. The token is in `solution.data.tokens`. */
final class ProtonCustomTaskRequest extends AbstractRequest
{
    protected const TASK_TYPE = 'CustomTask';
    protected const TASK_CLASS = 'proton';

    /** `pageAction` value for a login. */
    public const ACTION_LOGIN = 'login';

    /** `pageAction` value for a registration. */
    public const ACTION_SIGNUP = 'signup';

    /**
     * @param string      $humanVerificationToken `HumanVerificationToken` (sent in `metadata`): the token from the site's response.
     * @param string      $pageAction             Action on the site: {@see ACTION_LOGIN} or {@see ACTION_SIGNUP}.
     * @param string|null $userAgent              Browser User-Agent.
     */
    public function __construct(
        public readonly string $websiteUrl,
        public readonly string $humanVerificationToken,
        public readonly string $pageAction,
        public readonly ProxyInfo $proxy,
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
        self::requireNonEmpty('metadata.HumanVerificationToken', $this->humanVerificationToken);
        self::requireNonEmpty('metadata.pageAction', $this->pageAction);
        if ($this->pageAction !== self::ACTION_LOGIN && $this->pageAction !== self::ACTION_SIGNUP) {
            throw new ValidationException("metadata.pageAction must be 'login' or 'signup'");
        }
    }

    protected function fields(): array
    {
        return [
            'websiteURL' => $this->websiteUrl,
            'metadata' => [
                'HumanVerificationToken' => $this->humanVerificationToken,
                'pageAction' => $this->pageAction,
            ],
            'userAgent' => $this->userAgent,
        ] + self::proxyFields($this->proxy);
    }
}
