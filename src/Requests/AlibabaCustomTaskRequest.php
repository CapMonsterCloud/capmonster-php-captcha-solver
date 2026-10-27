<?php

declare(strict_types=1);

namespace Zennolab\CapMonsterCloud\Requests;

use Zennolab\CapMonsterCloud\GetResultTimeouts;
use Zennolab\CapMonsterCloud\ProxyInfo;

/**
 * Alibaba CAPTCHA / WAF (`CustomTask`, class `alibaba`).
 *
 * Only `sceneId` and `prefix` are always required; set the other metadata only if the target website uses it.
 */
final class AlibabaCustomTaskRequest extends AbstractRequest
{
    protected const TASK_TYPE = 'CustomTask';
    protected const TASK_CLASS = 'alibaba';

    /**
     * @param string      $sceneId        Captcha scenario identifier, e.g. `1ww7426c`.
     * @param string      $prefix         Subdomain of the request URL that loads the task, e.g. `dlw3kug` for
     *                                    `https://dlw3kug.captcha-open.example.aliyuncs.com/`.
     * @param string|null $userId         Unique user/session identifier on the website side.
     * @param string|null $userUserId     Additional (secondary) user identifier.
     * @param string|null $verifyType     Version/type of the verification mechanism, e.g. `1.0`.
     * @param string|null $region         Server region, e.g. `sgp` (`cn` gives `WEB#` tokens, `sgp` gives `SG_WEB#`).
     * @param string|null $userCertifyId  Verification ID of the current captcha session (`UserCertifyId`).
     * @param string|null $apiGetLib      Link to the captcha JS library (may change on every page render).
     * @param string|null $punishUrl      Full URL (with query) of the verification page, for websites where Alibaba
     *                                    returns a separate `/punish` URL.
     * @param bool|null   $cookieRequired Return cookies together with the solution (use `true` for Alibaba WAF sites).
     */
    public function __construct(
        public readonly string $websiteUrl,
        public readonly string $sceneId,
        public readonly string $prefix,
        public readonly ?ProxyInfo $proxy = null,
        public readonly ?string $userAgent = null,
        public readonly ?string $userId = null,
        public readonly ?string $userUserId = null,
        public readonly ?string $verifyType = null,
        public readonly ?string $region = null,
        public readonly ?string $userCertifyId = null,
        public readonly ?string $apiGetLib = null,
        public readonly ?string $punishUrl = null,
        public readonly ?bool $cookieRequired = null,
    ) {
    }

    public function timeouts(): GetResultTimeouts
    {
        return new GetResultTimeouts(1, 3, 120);
    }

    protected function validate(): void
    {
        self::requireNonEmpty('websiteURL', $this->websiteUrl);
        self::requireNonEmpty('metadata.sceneId', $this->sceneId);
        self::requireNonEmpty('metadata.prefix', $this->prefix);
    }

    protected function fields(): array
    {
        return [
            'websiteURL' => $this->websiteUrl,
            'metadata' => self::withoutNulls([
                'sceneId' => $this->sceneId,
                'prefix' => $this->prefix,
                'userId' => $this->userId,
                'userUserId' => $this->userUserId,
                'verifyType' => $this->verifyType,
                'region' => $this->region,
                'UserCertifyId' => $this->userCertifyId,
                'apiGetLib' => $this->apiGetLib,
                'punishUrl' => $this->punishUrl,
                'cookieRequired' => $this->cookieRequired,
            ]),
            'userAgent' => $this->userAgent,
        ] + self::proxyFields($this->proxy);
    }
}
