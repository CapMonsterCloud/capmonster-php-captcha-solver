<?php

declare(strict_types=1);

namespace Zennolab\CapMonsterCloud\Requests;

use Zennolab\CapMonsterCloud\GetResultTimeouts;

/** reCAPTCHA v3 Enterprise (`RecaptchaV3EnterpriseTask`). Proxies are not supported. */
final class RecaptchaV3EnterpriseRequest extends AbstractRequest
{
    protected const TASK_TYPE = 'RecaptchaV3EnterpriseTask';

    /**
     * @param string      $websiteKey The site key (`...enterprise.js?render=THIS_ONE`).
     * @param float|null  $minScore   Minimum score of the token, 0.1..0.9.
     * @param string|null $pageAction Value of the `action` parameter, if it differs from the default `verify`.
     */
    public function __construct(
        public readonly string $websiteUrl,
        public readonly string $websiteKey,
        public readonly ?float $minScore = null,
        public readonly ?string $pageAction = null,
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
        RecaptchaV3ProxylessRequest::checkMinScore($this->minScore);
    }

    protected function fields(): array
    {
        return [
            'websiteURL' => $this->websiteUrl,
            'websiteKey' => $this->websiteKey,
            'minScore' => $this->minScore,
            'pageAction' => $this->pageAction,
        ];
    }
}
