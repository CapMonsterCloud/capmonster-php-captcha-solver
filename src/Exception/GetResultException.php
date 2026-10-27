<?php

declare(strict_types=1);

namespace CapMonsterCloud\Exception;

/** `getTaskResult` returned `errorId != 0` (e.g. `ERROR_CAPTCHA_UNSOLVABLE`). */
class GetResultException extends ApiException
{
}
