<?php

declare(strict_types=1);

namespace CapMonsterCloud\Requests;

use CapMonsterCloud\GetResultTimeouts;

/** FengkongCloud (`CustomTask`, class `fengkongcloud`). The token is in `solution.data.token`. */
final class FengkongCloudCustomTaskRequest extends AbstractRequest
{
    protected const TASK_TYPE = 'CustomTask';
    protected const TASK_CLASS = 'fengkongcloud';

    /**
     * @param string      $websiteKey The `siteKey` value found on the page (the `appid` parameter of the `/conf` request).
     * @param string|null $apiJsUrl   Link to `smcp.min.js`, e.g. `https://example.com/path/smcp.min.js`.
     * @param string|null $userAgent  Browser User-Agent.
     */
    public function __construct(
        public readonly string $websiteUrl,
        public readonly string $websiteKey,
        public readonly ?string $apiJsUrl = null,
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
        $metadata = self::withoutNulls(['apiJsUrl' => $this->apiJsUrl]);

        return [
            'websiteURL' => $this->websiteUrl,
            'websiteKey' => $this->websiteKey,
            'metadata' => $metadata === [] ? null : $metadata,
            'userAgent' => $this->userAgent,
        ];
    }
}
