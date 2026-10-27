<?php

declare(strict_types=1);

namespace Zennolab\CapMonsterCloud;

use Zennolab\CapMonsterCloud\Exception\CapMonsterException;
use Zennolab\CapMonsterCloud\Exception\CreateTaskException;
use Zennolab\CapMonsterCloud\Exception\GetBalanceException;
use Zennolab\CapMonsterCloud\Exception\GetResultException;
use Zennolab\CapMonsterCloud\Exception\MalformedResponseException;
use Zennolab\CapMonsterCloud\Exception\TimeoutException;
use Zennolab\CapMonsterCloud\Exception\UnexpectedHttpStatusException;
use Zennolab\CapMonsterCloud\Http\CurlTransport;
use Zennolab\CapMonsterCloud\Http\HttpTransport;
use Zennolab\CapMonsterCloud\Requests\CaptchaRequest;

/** CapMonster Cloud client: createTask / getTaskResult / getBalance and the high-level {@see solve()}. */
final class CapMonsterClient
{
    /** SDK version, also sent in the User-Agent. Bumped on every release (see CHANGELOG.md). */
    public const VERSION = '2.0.0';

    /** HTTP statuses that `getTaskResult` sometimes returns while the task is still being processed. */
    private const PROCESSING_STATUSES = [500, 503];

    private readonly HttpTransport $transport;

    public function __construct(
        private readonly ClientOptions $options,
        ?HttpTransport $transport = null,
    ) {
        $this->transport = $transport ?? new CurlTransport(
            'Zennolab.CapMonsterCloud.Client.PHP/' . self::VERSION,
            $options->clientTimeout,
            $options->clientProxy,
        );
    }

    public function options(): ClientOptions
    {
        return $this->options;
    }

    /**
     * Account balance in USD.
     *
     * @throws CapMonsterException
     */
    public function getBalance(): float
    {
        $response = $this->call('getBalance', ['clientKey' => $this->options->apiKey]);
        if ((int) $response['errorId'] !== 0) {
            throw new GetBalanceException('getBalance', ...self::error($response));
        }
        if (!isset($response['balance']) || !is_numeric($response['balance'])) {
            throw new MalformedResponseException('getBalance: no `balance` field');
        }

        return (float) $response['balance'];
    }

    /**
     * Creates a task and returns its id (low-level; most users want {@see solve()}).
     *
     * @throws CapMonsterException
     */
    public function createTask(CaptchaRequest $request): int
    {
        $response = $this->call('createTask', [
            'clientKey' => $this->options->apiKey,
            'task' => $request->toTask(),
            'softId' => $this->options->softId,
        ]);
        if ((int) $response['errorId'] !== 0) {
            throw new CreateTaskException('createTask', ...self::error($response));
        }
        if (!isset($response['taskId']) || !is_numeric($response['taskId'])) {
            throw new MalformedResponseException('createTask: no `taskId` field');
        }

        return (int) $response['taskId'];
    }

    /**
     * One `getTaskResult` call (low-level).
     *
     * @throws CapMonsterException
     */
    public function getTaskResult(int $taskId): TaskResult
    {
        try {
            $response = $this->call('getTaskResult', ['clientKey' => $this->options->apiKey, 'taskId' => $taskId]);
        } catch (UnexpectedHttpStatusException $e) {
            if (in_array($e->status, self::PROCESSING_STATUSES, true)) {
                return TaskResult::processing();
            }
            throw $e;
        }
        if ((int) $response['errorId'] !== 0) {
            throw new GetResultException('getTaskResult', ...self::error($response));
        }
        if (($response['status'] ?? null) !== 'ready') {
            return TaskResult::processing();
        }
        $solution = $response['solution'] ?? [];

        return TaskResult::ready(is_array($solution) ? $solution : ['value' => $solution]);
    }

    /**
     * Creates a task, polls until it is solved and returns the `solution` object
     * (e.g. `['gRecaptchaResponse' => '...']`, `['token' => '...']`, `['text' => '...']`).
     *
     * @return array<string, mixed>
     *
     * @throws TimeoutException    if the captcha type's maximum waiting time is exceeded
     * @throws CapMonsterException on any other failure
     */
    public function solve(CaptchaRequest $request): array
    {
        $timeouts = $this->options->timeoutsOverride ?? $request->timeouts();
        $taskId = $this->createTask($request);

        $deadline = self::now() + $timeouts->timeout;
        self::sleep($timeouts->firstRequestDelay);
        while (self::now() < $deadline) {
            $result = $this->getTaskResult($taskId);
            if ($result->isReady) {
                return $result->solution ?? [];
            }
            self::sleep(min($timeouts->requestsInterval, max(0.0, $deadline - self::now())));
        }

        throw new TimeoutException($taskId, $timeouts->timeout);
    }

    /**
     * @param array<string, mixed> $body
     *
     * @return array<string, mixed> decoded JSON with at least `errorId`
     */
    private function call(string $method, array $body): array
    {
        $url = rtrim($this->options->serviceUrl, '/') . '/' . $method;
        $response = $this->transport->post($url, $body);

        $data = json_decode($response->body, true);
        $isEnvelope = is_array($data) && array_key_exists('errorId', $data);
        $isSuccess = $response->status >= 200 && $response->status < 300;

        // An error JSON (errorId != 0) is reported as an API error whatever the HTTP status is.
        if ($isEnvelope && ($isSuccess || (int) $data['errorId'] !== 0)) {
            return $data;
        }
        if (!$isSuccess) {
            throw new UnexpectedHttpStatusException($method, $response->status);
        }

        throw new MalformedResponseException(sprintf('%s: unexpected response body: %.200s', $method, $response->body));
    }

    /**
     * @param array<string, mixed> $response
     *
     * @return array{0: string, 1: string} errorCode, errorDescription
     */
    private static function error(array $response): array
    {
        return [(string) ($response['errorCode'] ?? ''), (string) ($response['errorDescription'] ?? '')];
    }

    private static function now(): float
    {
        return hrtime(true) / 1e9;
    }

    private static function sleep(float $seconds): void
    {
        if ($seconds > 0) {
            usleep((int) ($seconds * 1_000_000));
        }
    }
}
