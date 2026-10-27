<?php

declare(strict_types=1);

namespace CapMonsterCloud\Requests;

/** Image grid of a reCAPTCHA click challenge (`metadata.Grid`). */
enum RecaptchaGrid: string
{
    case Grid3x3 = '3x3';
    case Grid4x4 = '4x4';
    case Grid1x1 = '1x1';
}
