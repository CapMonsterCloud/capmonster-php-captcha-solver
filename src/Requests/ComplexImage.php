<?php

declare(strict_types=1);

namespace CapMonsterCloud\Requests;

use CapMonsterCloud\Exception\ValidationException;
use CapMonsterCloud\GetResultTimeouts;

/** @internal Parts shared by the `ComplexImageTask` requests. */
final class ComplexImage
{
    public static function timeouts(): GetResultTimeouts
    {
        return new GetResultTimeouts(0.35, 0.2, 10);
    }

    /** At least one of the two image arrays must be given. */
    public static function requireImages(?array $urls, ?array $base64): void
    {
        if ($urls === null && $base64 === null) {
            throw new ValidationException('at least one of imageUrls / imagesBase64 must be set');
        }
    }

    /** When given, the list must hold 1..$max non-empty strings. */
    public static function checkImageList(string $field, ?array $list, int $max): void
    {
        if ($list === null) {
            return;
        }
        if (!array_is_list($list)) {
            throw new ValidationException("{$field} must be a list");
        }
        $count = count($list);
        if ($count === 0) {
            throw new ValidationException("{$field}: at least one entry expected, got 0");
        }
        if ($count > $max) {
            throw new ValidationException("{$field}: at most {$max} entries allowed, got {$count}");
        }
        foreach ($list as $i => $item) {
            if (!is_string($item) || $item === '') {
                throw new ValidationException("{$field}[{$i}] must be a non-empty string");
            }
        }
    }

    private function __construct()
    {
    }
}
