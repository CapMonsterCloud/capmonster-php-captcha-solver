<?php

declare(strict_types=1);

namespace CapMonsterCloud\Requests;

use CapMonsterCloud\GetResultTimeouts;
use CapMonsterCloud\ProxyInfo;

/**
 * Cloudflare Turnstile / Challenge / Waiting Room (`TurnstileTask`).
 *
 * Pick the mode with a factory; each one takes only the fields the API accepts for that mode:
 * {@see standard()}, {@see challengeToken()}, {@see challengeCookie()}, {@see waitRoom()}.
 * `websiteKey` is the Turnstile site key, e.g. `0x4AAAAAAABUYP0XeMJF0xoy`; for `cf_clearance` / `wait_room`
 * any string may be passed (e.g. `xxxxxx`).
 */
final class TurnstileRequest extends AbstractRequest
{
    protected const TASK_TYPE = 'TurnstileTask';

    private function __construct(
        public readonly string $websiteUrl,
        public readonly string $websiteKey,
        /** `null` (regular widget), `token`, `cf_clearance` or `wait_room`. */
        public readonly ?string $cloudflareTaskType,
        public readonly ?ProxyInfo $proxy = null,
        public readonly ?string $userAgent = null,
        public readonly ?string $pageAction = null,
        public readonly ?string $data = null,
        public readonly ?string $pageData = null,
        public readonly ?string $apiJsUrl = null,
        public readonly ?string $htmlPageBase64 = null,
    ) {
    }

    /**
     * Regular Turnstile widget (manual, non-interactive and invisible subtypes are detected automatically).
     * The solution is `['token' => ...]`.
     *
     * @param string|null $pageAction The `action` found in the callback used when the captcha is loaded.
     * @param string|null $data       The `cData` value of the widget.
     */
    public static function standard(
        string $websiteUrl,
        string $websiteKey,
        ?ProxyInfo $proxy = null,
        ?string $userAgent = null,
        ?string $pageAction = null,
        ?string $data = null,
    ): self {
        return new self($websiteUrl, $websiteKey, null, $proxy, $userAgent, $pageAction, $data);
    }

    /**
     * Cloudflare Challenge solved to a token (`cloudflareTaskType = token`). The values are the parameters passed
     * to `window.turnstile.render(el, paramsObj)`.
     *
     * @param string      $pageAction The `action` of the callback; usually `managed` or `non-interactive`.
     * @param string      $data       The `cData` parameter.
     * @param string      $pageData   The `chlPageData` parameter.
     * @param string|null $apiJsUrl   Link to the captcha script.
     */
    public static function challengeToken(
        string $websiteUrl,
        string $websiteKey,
        string $pageAction,
        string $userAgent,
        string $data,
        string $pageData,
        ?ProxyInfo $proxy = null,
        ?string $apiJsUrl = null,
    ): self {
        return new self($websiteUrl, $websiteKey, 'token', $proxy, $userAgent, $pageAction, $data, $pageData, $apiJsUrl);
    }

    /**
     * Cloudflare Challenge solved to the `cf_clearance` cookie. Your own proxy is mandatory.
     *
     * @param string $htmlPageBase64 Base64 of the "Just a moment" HTML page returned with status 403.
     */
    public static function challengeCookie(
        string $websiteUrl,
        string $websiteKey,
        string $htmlPageBase64,
        string $userAgent,
        ProxyInfo $proxy,
    ): self {
        return new self($websiteUrl, $websiteKey, 'cf_clearance', $proxy, $userAgent, htmlPageBase64: $htmlPageBase64);
    }

    /**
     * Cloudflare Waiting Room; the solution is cookies. Your own proxy is mandatory.
     *
     * @param string $htmlPageBase64 Base64 of the page containing `<title>Waiting Room powered by Cloudflare</title>`.
     */
    public static function waitRoom(
        string $websiteUrl,
        string $websiteKey,
        string $htmlPageBase64,
        string $userAgent,
        ProxyInfo $proxy,
    ): self {
        return new self($websiteUrl, $websiteKey, 'wait_room', $proxy, $userAgent, htmlPageBase64: $htmlPageBase64);
    }

    public function timeouts(): GetResultTimeouts
    {
        return new GetResultTimeouts(1, 1, 80);
    }

    protected function validate(): void
    {
        self::requireNonEmpty('websiteURL', $this->websiteUrl);
        self::requireNonEmpty('websiteKey', $this->websiteKey);
        switch ($this->cloudflareTaskType) {
            case 'token':
                self::requireNonEmpty('pageAction', $this->pageAction);
                self::requireNonEmpty('userAgent', $this->userAgent);
                self::requireNonEmpty('data', $this->data);
                self::requireNonEmpty('pageData', $this->pageData);
                break;
            case 'cf_clearance':
            case 'wait_room':
                self::requireNonEmpty('htmlPageBase64', $this->htmlPageBase64);
                self::requireNonEmpty('userAgent', $this->userAgent);
                break;
        }
    }

    protected function fields(): array
    {
        return [
            'websiteURL' => $this->websiteUrl,
            'websiteKey' => $this->websiteKey,
            'cloudflareTaskType' => $this->cloudflareTaskType,
            'pageAction' => $this->pageAction,
            'userAgent' => $this->userAgent,
            'data' => $this->data,
            'pageData' => $this->pageData,
            'apiJsUrl' => $this->apiJsUrl,
            'htmlPageBase64' => $this->htmlPageBase64,
        ] + self::proxyFields($this->proxy);
    }
}
