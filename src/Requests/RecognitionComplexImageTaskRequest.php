<?php

declare(strict_types=1);

namespace CapMonsterCloud\Requests;

use CapMonsterCloud\GetResultTimeouts;

/**
 * Recognition modules of `ComplexImageTask` (class `recognition`), no proxy needed.
 *
 * The module is chosen by `task` (`metadata.Task`). Modules documented today, with their answer kind:
 * `shein` (click coordinates), `MathSum` (text), `dli` (text), `bls_3x3` (grid, needs `taskArgument`), `baidu`,
 * `oocl_rotate_new`, `oocl_rotate_double_new`, `betpunch_3x3_rotate` (rotation angles),
 * `portugal_text_find_icon` (needs `taskArgument`), `bills_audio` (audio, `payloadType = PAYLOAD_AUDIO`).
 * Most modules take exactly one image.
 */
final class RecognitionComplexImageTaskRequest extends AbstractRequest
{
    protected const TASK_TYPE = 'ComplexImageTask';
    protected const TASK_CLASS = 'recognition';

    /** `metadata.PayloadType` for audio modules (`imagesBase64` then holds base64 audio). */
    public const PAYLOAD_AUDIO = 'Audio';

    /**
     * @param list<string> $imagesBase64 Base64 images without a `data:...;base64,` prefix; at least one.
     * @param string|null  $taskArgument Module argument, e.g. the number to find for `bls_3x3` (`"123"`) or the
     *                                   instruction text for `portugal_text_find_icon`.
     * @param string|null  $payloadType  {@see PAYLOAD_AUDIO} for audio modules.
     */
    public function __construct(
        public readonly array $imagesBase64,
        public readonly string $task,
        public readonly ?string $taskArgument = null,
        public readonly ?string $payloadType = null,
        public readonly ?string $userAgent = null,
    ) {
    }

    public function timeouts(): GetResultTimeouts
    {
        return ComplexImage::timeouts();
    }

    protected function validate(): void
    {
        self::requireNonEmpty('metadata.Task', $this->task);
        ComplexImage::checkImageList('imagesBase64', $this->imagesBase64, PHP_INT_MAX);
    }

    protected function fields(): array
    {
        return [
            'imagesBase64' => $this->imagesBase64,
            'metadata' => self::withoutNulls([
                'Task' => $this->task,
                'TaskArgument' => $this->taskArgument,
                'PayloadType' => $this->payloadType,
            ]),
            'userAgent' => $this->userAgent,
        ];
    }
}
