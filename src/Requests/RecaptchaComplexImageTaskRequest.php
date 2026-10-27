<?php

declare(strict_types=1);

namespace Zennolab\CapMonsterCloud\Requests;

use Zennolab\CapMonsterCloud\Exception\ValidationException;
use Zennolab\CapMonsterCloud\GetResultTimeouts;

/**
 * reCAPTCHA v2 image click task (`ComplexImageTask`, class `recaptcha`). No proxy is needed.
 *
 * Pass the full 4x4 / 3x3 image or a new 1x1 tile either by URL or as base64 (exactly one image), and the task
 * either by its English name (`task`) or by its technical id (`taskDefinition`).
 */
final class RecaptchaComplexImageTaskRequest extends AbstractRequest
{
    protected const TASK_TYPE = 'ComplexImageTask';
    protected const TASK_CLASS = 'recaptcha';

    /**
     * @param string|null        $task           Task name in English, e.g. `Click on traffic lights` (`metadata.Task`).
     * @param string|null        $taskDefinition Technical task id, e.g. `/m/015qff`, from the `/recaptcha/<api>/reload`
     *                                           or `/userverify` response (`metadata.TaskDefinition`).
     * @param list<string>|null  $imageUrls      Image link (array of one); required if `imagesBase64` is not set.
     * @param list<string>|null  $imagesBase64   Base64 image without the `data:` prefix (array of one).
     * @param string|null        $userAgent      User-Agent used to load images passed as URLs.
     */
    public function __construct(
        public readonly RecaptchaGrid $grid,
        public readonly ?string $task = null,
        public readonly ?string $taskDefinition = null,
        public readonly ?array $imageUrls = null,
        public readonly ?array $imagesBase64 = null,
        public readonly ?string $userAgent = null,
        public readonly ?string $websiteUrl = null,
    ) {
    }

    public function timeouts(): GetResultTimeouts
    {
        return ComplexImage::timeouts();
    }

    protected function validate(): void
    {
        if (($this->task ?? '') === '' && ($this->taskDefinition ?? '') === '') {
            throw new ValidationException('metadata: either task or taskDefinition must be set');
        }
        ComplexImage::requireImages($this->imageUrls, $this->imagesBase64);
        ComplexImage::checkImageList('imageUrls', $this->imageUrls, 1);
        ComplexImage::checkImageList('imagesBase64', $this->imagesBase64, 1);
    }

    protected function fields(): array
    {
        return [
            'imageUrls' => $this->imageUrls,
            'imagesBase64' => $this->imagesBase64,
            'metadata' => self::withoutNulls([
                'Grid' => $this->grid->value,
                'Task' => $this->task,
                'TaskDefinition' => $this->taskDefinition,
            ]),
            'userAgent' => $this->userAgent,
            'websiteURL' => $this->websiteUrl,
        ];
    }
}
