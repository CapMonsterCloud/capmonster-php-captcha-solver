<?php

declare(strict_types=1);

namespace CapMonsterCloud\Requests;

/**
 * Recognition modules of `ImageToTextTask` (`capMonsterModule`), as listed in
 * https://docs.capmonster.cloud/docs/captchas/ImageToText/module-name.txt.
 * The list grows: any other module name can be passed as a plain string.
 */
final class TextModule
{
    public const AMAZON = 'amazon';
    public const APPLE = 'apple';
    public const BOTDETECT = 'botdetect';
    public const FACEBOOK = 'facebook';
    public const GMX = 'gmx';
    public const GOOGLE = 'google';
    public const HOTMAIL = 'hotmail';
    public const MAILRU = 'mailru';
    public const OKENG = 'okeng';
    public const OKRUS = 'okrus';
    public const PARTIALLYBLUR = 'partiallyblur';
    public const RAMBLERRUS = 'ramblerrus';
    public const RAMBLERRUSNEW = 'ramblerrusnew';
    public const SOLVEMEDIA = 'solvemedia';
    public const STEAM = 'steam';
    public const VK = 'vk';
    public const WHATSAPP = 'whatsapp';
    public const WIKIMEDIA_PADDLE_ENSEMBLE = 'wikimedia_paddle_ensemble';
    public const YANDEX = 'yandex';
    /** Two-word Yandex captcha. */
    public const YANDEXNEW = 'yandexnew';
    public const YANDEXWAVE = 'yandexwave';
    /** Latin Yandex captcha. */
    public const YANDEXWAVELATIN = 'yandexwavelatin';
    /** Do not combine with `math: true`. */
    public const CAPTCHA_MATH = 'captcha_math';
    public const SAT = 'sat';
    public const BLS_TEXT = 'bls_text';
    /** All other text captchas. */
    public const UNIVERSAL = 'universal';

    private function __construct()
    {
    }
}
