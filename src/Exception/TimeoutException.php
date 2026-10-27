<?php

declare(strict_types=1);

namespace CapMonsterCloud\Exception;

/** The solution did not arrive within the maximum waiting time of the task type. */
class TimeoutException extends CapMonsterException
{
    public function __construct(
        public readonly int $taskId,
        public readonly float $timeout,
    ) {
        parent::__construct(sprintf('failed to get a solution of task %d within %.1f s', $taskId, $timeout));
    }
}
