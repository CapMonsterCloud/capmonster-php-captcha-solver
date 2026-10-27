<?php

declare(strict_types=1);

namespace CapMonsterCloud\Exception;

/** The request is inconsistent (missing or empty field, value out of range...). Thrown locally, before anything is sent. */
class ValidationException extends CapMonsterException
{
}
