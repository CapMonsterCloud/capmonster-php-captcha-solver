<?php

declare(strict_types=1);

namespace CapMonsterCloud\Requests;

use CapMonsterCloud\Exception\ValidationException;
use CapMonsterCloud\GetResultTimeouts;

/** HeyTap (`CustomTask`, class `heytap`). The token is in `solution.data.token`. */
final class HeyTapCustomTaskRequest extends AbstractRequest
{
    protected const TASK_TYPE = 'CustomTask';
    protected const TASK_CLASS = 'heytap';

    /**
     * @param string      $websiteKey The `siteKey` value found on the page (the `ak` parameter of the captcha API URL).
     * @param string|null $apiServer  Subdomain of the HeyTap API server, only the domain without the `https://` prefix.
     *                                Required if it differs from the default API server.
     * @param string|null $userAgent  Browser User-Agent.
     */
    public function __construct(
        public readonly string $websiteUrl,
        public readonly string $websiteKey,
        public readonly ?string $apiServer = null,
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
        if ($this->apiServer !== null && preg_match('#^https?://#i', $this->apiServer) === 1) {
            throw new ValidationException('metadata.apiServer must be a domain without the https:// prefix');
        }
    }

    protected function fields(): array
    {
        $metadata = self::withoutNulls(['apiServer' => $this->apiServer]);

        return [
            'websiteURL' => $this->websiteUrl,
            'websiteKey' => $this->websiteKey,
            'metadata' => $metadata === [] ? null : $metadata,
            'userAgent' => $this->userAgent,
        ];
    }
}
