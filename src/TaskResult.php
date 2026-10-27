<?php

declare(strict_types=1);

namespace CapMonsterCloud;

/** State of a task returned by {@see CapMonsterClient::getTaskResult()}. */
final class TaskResult
{
    /**
     * @param array<string, mixed>|null $solution The solution object (shape depends on the captcha type); null while processing.
     */
    private function __construct(
        public readonly bool $isReady,
        public readonly ?array $solution,
    ) {
    }

    public static function processing(): self
    {
        return new self(false, null);
    }

    /** @param array<string, mixed> $solution */
    public static function ready(array $solution): self
    {
        return new self(true, $solution);
    }
}
