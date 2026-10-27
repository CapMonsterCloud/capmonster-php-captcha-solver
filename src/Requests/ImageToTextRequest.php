<?php

declare(strict_types=1);

namespace Zennolab\CapMonsterCloud\Requests;

use Zennolab\CapMonsterCloud\Exception\ValidationException;
use Zennolab\CapMonsterCloud\GetResultTimeouts;

/** Text-on-image captcha (`ImageToTextTask`). The solution is `['text' => ...]`. */
final class ImageToTextRequest extends AbstractRequest
{
    protected const TASK_TYPE = 'ImageToTextTask';

    /**
     * @param string      $body                 Base64-encoded image, one line, without a `data:image/...;base64,` prefix.
     *                                          Use {@see fromFile()} to read an image from disk.
     * @param string|null $capMonsterModule     Recognition module, see {@see TextModule}.
     * @param int|null    $recognizingThreshold Minimum recognition confidence, 0..100.
     * @param bool|null   $case                 Case-sensitive text.
     * @param bool|null   $numeric              Only digits (sent as 0/1).
     * @param bool|null   $math                 The image contains an arithmetic expression to calculate.
     */
    public function __construct(
        public readonly string $body,
        public readonly ?string $capMonsterModule = null,
        public readonly ?int $recognizingThreshold = null,
        public readonly ?bool $case = null,
        public readonly ?bool $numeric = null,
        public readonly ?bool $math = null,
    ) {
    }

    /**
     * Reads the image file and base64-encodes it. Optional arguments are those of the constructor.
     *
     * @throws ValidationException if the file cannot be read
     */
    public static function fromFile(
        string $path,
        ?string $capMonsterModule = null,
        ?int $recognizingThreshold = null,
        ?bool $case = null,
        ?bool $numeric = null,
        ?bool $math = null,
    ): self {
        $bytes = @file_get_contents($path);
        if ($bytes === false) {
            throw new ValidationException("cannot read image file {$path}");
        }

        return new self(base64_encode($bytes), $capMonsterModule, $recognizingThreshold, $case, $numeric, $math);
    }

    public function timeouts(): GetResultTimeouts
    {
        return new GetResultTimeouts(0.35, 0.2, 10);
    }

    protected function validate(): void
    {
        self::requireNonEmpty('body', $this->body);
        if (str_starts_with($this->body, 'data:')) {
            throw new ValidationException('body must be plain base64, without the "data:...;base64," prefix');
        }
        if ($this->recognizingThreshold !== null && ($this->recognizingThreshold < 0 || $this->recognizingThreshold > 100)) {
            throw new ValidationException("recognizingThreshold must be between 0 and 100, got {$this->recognizingThreshold}");
        }
    }

    protected function fields(): array
    {
        return [
            'body' => $this->body,
            'capMonsterModule' => $this->capMonsterModule,
            'recognizingThreshold' => $this->recognizingThreshold,
            'case' => $this->case,
            'numeric' => $this->numeric === null ? null : (int) $this->numeric,
            'math' => $this->math,
        ];
    }
}
