<?php

declare(strict_types=1);

namespace CapMonsterCloud;

/** Client settings. Use named arguments to change only what you need. */
final class ClientOptions
{
    public const DEFAULT_SERVICE_URL = 'https://api.capmonster.cloud';
    public const DEFAULT_SOFT_ID = 57;

    /**
     * @param float                  $clientTimeout    Timeout of ONE HTTP request in seconds (not of the whole solving).
     * @param ClientProxy|null       $clientProxy      Proxy for the SDK's own requests to the API.
     * @param GetResultTimeouts|null $timeoutsOverride Replaces the per-captcha-type polling schedule.
     */
    public function __construct(
        public readonly string $apiKey,
        public readonly string $serviceUrl = self::DEFAULT_SERVICE_URL,
        public readonly int $softId = self::DEFAULT_SOFT_ID,
        public readonly float $clientTimeout = 20.0,
        public readonly ?ClientProxy $clientProxy = null,
        public readonly ?GetResultTimeouts $timeoutsOverride = null,
    ) {
    }

    /** Keeps the API key out of var_dump()/print_r() output. */
    public function __debugInfo(): array
    {
        return [
            'apiKey' => '***',
            'serviceUrl' => $this->serviceUrl,
            'softId' => $this->softId,
            'clientTimeout' => $this->clientTimeout,
            'clientProxy' => $this->clientProxy,
            'timeoutsOverride' => $this->timeoutsOverride,
        ];
    }
}
