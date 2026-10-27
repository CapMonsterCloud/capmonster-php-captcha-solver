<?php

declare(strict_types=1);

namespace CapMonsterCloud\Requests;

use CapMonsterCloud\Exception\ValidationException;
use CapMonsterCloud\GetResultTimeouts;

/**
 * A task that can be sent to CapMonster Cloud.
 *
 * Every built-in request extends {@see AbstractRequest}. Implement this interface directly only
 * for a task type the SDK does not know yet.
 */
interface CaptchaRequest
{
    /** Polling schedule recommended for this captcha type. */
    public function timeouts(): GetResultTimeouts;

    /**
     * The `task` object for `createTask`.
     *
     * @return array<string, mixed>
     *
     * @throws ValidationException if the request is inconsistent
     */
    public function toTask(): array;
}
