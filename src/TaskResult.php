<?php

declare(strict_types=1);

namespace CapMonsterCloud;

/** State of a task returned by {@see CapMonsterClient::getTaskResult()}. */
final class TaskResult
{
    /**
     * @param array<string, mixed>|null $solution The solution object (shape depends on the captcha type); null while processing.
     * @param float|null $cost Solution cost in US dollars (USD); returned only in the ready response, null otherwise.
     */
    private function __construct(
        public readonly bool $isReady,
        public readonly ?array $solution,
        public readonly ?float $cost = null,
    ) {
    }

    public static function processing(): self
    {
        return new self(false, null);
    }

    /** @param array<string, mixed> $solution */
    public static function ready(array $solution, ?float $cost = null): self
    {
        return new self(true, $solution, $cost);
    }
}
