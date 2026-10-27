<?php

declare(strict_types=1);

namespace Zennolab\CapMonsterCloud\Exception;

/** The API answered with an unexpected HTTP status code and no error JSON. */
class UnexpectedHttpStatusException extends CapMonsterException
{
    public function __construct(
        public readonly string $method,
        public readonly int $status,
    ) {
        parent::__construct("unexpected HTTP status {$status} from {$method}", $status);
    }
}
