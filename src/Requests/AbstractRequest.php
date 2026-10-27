<?php

declare(strict_types=1);

namespace CapMonsterCloud\Requests;

use CapMonsterCloud\Exception\ValidationException;
use CapMonsterCloud\ProxyInfo;

/**
 * Base of all built-in requests.
 *
 * Conventions (keep them when adding a type):
 * - required fields are leading constructor arguments, optional ones are nullable with a `null` default
 *   (use named arguments); when a type has mutually exclusive parameter sets, the constructor is private
 *   and each set gets its own static factory;
 * - `"type"` / `"class"` come from TASK_TYPE / TASK_CLASS, never from a property;
 * - {@see fields()} returns the task fields with exact API names (`websiteURL`, not `websiteUrl`);
 *   top-level `null` values are dropped;
 * - local checks that the API would reject anyway live in {@see validate()}.
 */
abstract class AbstractRequest implements CaptchaRequest
{
    /** Value of the task `type` field. */
    protected const TASK_TYPE = '';
    /** Value of the task `class` field (CustomTask / ComplexImageTask only). */
    protected const TASK_CLASS = null;

    public function toTask(): array
    {
        $this->validate();
        $task = ['type' => static::TASK_TYPE];
        if (static::TASK_CLASS !== null) {
            $task['class'] = static::TASK_CLASS;
        }

        return $task + self::withoutNulls($this->fields());
    }

    /** @return array<string, mixed> */
    abstract protected function fields(): array;

    /** @throws ValidationException */
    protected function validate(): void
    {
    }

    /** @throws ValidationException */
    protected static function requireNonEmpty(string $field, ?string $value): void
    {
        if ($value === null || $value === '') {
            throw new ValidationException("{$field} must not be empty");
        }
    }

    /** @return array<string, string|int> */
    protected static function proxyFields(?ProxyInfo $proxy): array
    {
        return $proxy?->toArray() ?? [];
    }

    /**
     * @param array<string, mixed> $values
     *
     * @return array<string, mixed>
     */
    protected static function withoutNulls(array $values): array
    {
        return array_filter($values, static fn ($v) => $v !== null);
    }
}
