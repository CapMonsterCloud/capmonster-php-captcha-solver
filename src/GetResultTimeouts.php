<?php

declare(strict_types=1);

namespace Zennolab\CapMonsterCloud;

/** Polling schedule of `getTaskResult` for one task type. All values are in seconds. */
final class GetResultTimeouts
{
    /**
     * @param float $firstRequestDelay Pause between `createTask` and the first `getTaskResult`.
     * @param float $requestsInterval  Pause between two consecutive `getTaskResult` calls.
     * @param float $timeout           Total time after which a TimeoutException is thrown.
     */
    public function __construct(
        public readonly float $firstRequestDelay,
        public readonly float $requestsInterval,
        public readonly float $timeout,
    ) {
    }
}
