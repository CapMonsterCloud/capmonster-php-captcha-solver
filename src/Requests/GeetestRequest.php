<?php

declare(strict_types=1);

namespace CapMonsterCloud\Requests;

use CapMonsterCloud\GetResultTimeouts;
use CapMonsterCloud\ProxyInfo;

/**
 * GeeTest captcha (`GeeTestTask`), versions 3 and 4. Build it with {@see v3()} or {@see v4()}.
 *
 * Optional arguments of both factories:
 * - `geetestApiServerSubdomain`: GeeTest API server subdomain (must differ from `api.geetest.com`);
 * - `geetestGetLib`: path to the captcha script, **as a JSON string**;
 * - `userAgent`: browser User-Agent (use a current Windows one).
 */
final class GeetestRequest extends AbstractRequest
{
    protected const TASK_TYPE = 'GeeTestTask';

    /** @param array<string, mixed>|null $initParameters */
    private function __construct(
        public readonly string $websiteUrl,
        public readonly string $gt,
        public readonly int $version,
        public readonly ?string $challenge,
        public readonly ?array $initParameters,
        public readonly ?ProxyInfo $proxy,
        public readonly ?string $geetestApiServerSubdomain,
        public readonly ?string $geetestGetLib,
        public readonly ?string $userAgent,
    ) {
    }

    /**
     * GeeTest v3.
     *
     * @param string $websiteUrl URL of the page where the captcha is initialized (usually the `Referer` of the
     *                           `gettype.php` request, which may differ from the URL in the address bar).
     * @param string $gt         The `gt` key of the domain.
     * @param string $challenge  Dynamic key: obtain a fresh one before creating each task and before the captcha
     *                           is initialized on the page (afterwards it becomes invalid).
     */
    public static function v3(
        string $websiteUrl,
        string $gt,
        string $challenge,
        ?ProxyInfo $proxy = null,
        ?string $geetestApiServerSubdomain = null,
        ?string $geetestGetLib = null,
        ?string $userAgent = null,
    ): self {
        return new self($websiteUrl, $gt, 3, $challenge, null, $proxy, $geetestApiServerSubdomain, $geetestGetLib, $userAgent);
    }

    /**
     * GeeTest v4.
     *
     * @param string                    $gt             The `captcha_id`.
     * @param array<string, mixed>|null $initParameters Extra v4 parameters, e.g. `['riskType' => 'slide']`.
     */
    public static function v4(
        string $websiteUrl,
        string $gt,
        ?array $initParameters = null,
        ?ProxyInfo $proxy = null,
        ?string $geetestApiServerSubdomain = null,
        ?string $geetestGetLib = null,
        ?string $userAgent = null,
    ): self {
        return new self($websiteUrl, $gt, 4, null, $initParameters ?: null, $proxy, $geetestApiServerSubdomain, $geetestGetLib, $userAgent);
    }

    public function timeouts(): GetResultTimeouts
    {
        return new GetResultTimeouts(1, 1, 80);
    }

    protected function validate(): void
    {
        self::requireNonEmpty('websiteURL', $this->websiteUrl);
        self::requireNonEmpty('gt', $this->gt);
        if ($this->version === 3) {
            self::requireNonEmpty('challenge', $this->challenge);
        }
    }

    protected function fields(): array
    {
        return [
            'websiteURL' => $this->websiteUrl,
            'gt' => $this->gt,
            'version' => $this->version,
            'challenge' => $this->challenge,
            'initParameters' => $this->initParameters,
            'geetestApiServerSubdomain' => $this->geetestApiServerSubdomain,
            'geetestGetLib' => $this->geetestGetLib,
            'userAgent' => $this->userAgent,
        ] + self::proxyFields($this->proxy);
    }
}
