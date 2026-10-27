<?php

declare(strict_types=1);

namespace Zennolab\CapMonsterCloud\Exception;

/**
 * The API returned `errorId != 0`. `errorCode` is the stable identifier to branch on
 * (e.g. `ERROR_KEY_DOES_NOT_EXIST`, `ERROR_ZERO_BALANCE`, `ERROR_CAPTCHA_UNSOLVABLE`).
 */
class ApiException extends CapMonsterException
{
    public function __construct(
        public readonly string $method,
        public readonly string $errorCode,
        public readonly string $errorDescription,
    ) {
        parent::__construct("{$method} failed: [{$errorCode}] {$errorDescription}");
    }
}
