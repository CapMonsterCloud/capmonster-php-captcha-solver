<?php

declare(strict_types=1);

namespace CapMonsterCloud\Requests;

use CapMonsterCloud\Exception\ValidationException;
use CapMonsterCloud\GetResultTimeouts;

/** reCAPTCHA v3 (`RecaptchaV3TaskProxyless`). Proxies are not supported. */
final class RecaptchaV3ProxylessRequest extends AbstractRequest
{
    protected const TASK_TYPE = 'RecaptchaV3TaskProxyless';

    /**
     * @param string      $websiteKey   The reCAPTCHA v3 site key (`...api.js?render=THIS_ONE`).
     * @param float|null  $minScore     Minimum score of the token, 0.1..0.9.
     * @param string|null $pageAction   Widget action value, default `verify`.
     * @param bool|null   $isEnterprise Solve as reCAPTCHA v3 Enterprise.
     */
    public function __construct(
        public readonly string $websiteUrl,
        public readonly string $websiteKey,
        public readonly ?float $minScore = null,
        public readonly ?string $pageAction = null,
        public readonly ?bool $isEnterprise = null,
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
        self::checkMinScore($this->minScore);
    }

    protected function fields(): array
    {
        return [
            'websiteURL' => $this->websiteUrl,
            'websiteKey' => $this->websiteKey,
            'minScore' => $this->minScore,
            'pageAction' => $this->pageAction,
            'isEnterprise' => $this->isEnterprise,
        ];
    }

    /** @internal shared with RecaptchaV3EnterpriseRequest */
    public static function checkMinScore(?float $minScore): void
    {
        if ($minScore !== null && ($minScore < 0.1 || $minScore > 0.9)) {
            throw new ValidationException("minScore must be between 0.1 and 0.9, got {$minScore}");
        }
    }
}
