<?php

declare(strict_types=1);

namespace Zennolab\CapMonsterCloud\Tests;

use PHPUnit\Framework\TestCase;
use Zennolab\CapMonsterCloud\CapMonsterClient;
use Zennolab\CapMonsterCloud\ClientOptions;
use Zennolab\CapMonsterCloud\Exception\CapMonsterException;
use Zennolab\CapMonsterCloud\Exception\CreateTaskException;
use Zennolab\CapMonsterCloud\Exception\GetBalanceException;
use Zennolab\CapMonsterCloud\Exception\GetResultException;
use Zennolab\CapMonsterCloud\Exception\MalformedResponseException;
use Zennolab\CapMonsterCloud\Exception\TimeoutException;
use Zennolab\CapMonsterCloud\Exception\TransportException;
use Zennolab\CapMonsterCloud\Exception\UnexpectedHttpStatusException;
use Zennolab\CapMonsterCloud\Exception\ValidationException;
use Zennolab\CapMonsterCloud\GetResultTimeouts;
use Zennolab\CapMonsterCloud\Http\HttpResponse;
use Zennolab\CapMonsterCloud\Requests\RecaptchaV2Request;

final class ClientTest extends TestCase
{
    private FakeTransport $api;

    protected function setUp(): void
    {
        $this->api = new FakeTransport();
    }

    /** 10 ms schedule instead of the real 1 s / 3 s / 180 s one. */
    private function client(float $timeout = 0.3): CapMonsterClient
    {
        return new CapMonsterClient(
            new ClientOptions('KEY', serviceUrl: 'https://api.test/', timeoutsOverride: new GetResultTimeouts(0.01, 0.01, $timeout)),
            $this->api,
        );
    }

    private static function request(): RecaptchaV2Request
    {
        return new RecaptchaV2Request('https://example.com', 'SITEKEY');
    }

    public function testSolveCreatesTaskAndPollsUntilReady(): void
    {
        $this->api
            ->onJson('createTask', ['errorId' => 0, 'taskId' => 42])
            ->onJson('getTaskResult', ['errorId' => 0, 'status' => 'processing'])
            ->onJson('getTaskResult', ['errorId' => 0, 'status' => 'ready', 'solution' => ['gRecaptchaResponse' => 'TOKEN']]);

        $solution = $this->client()->solve(self::request());

        self::assertSame(['gRecaptchaResponse' => 'TOKEN'], $solution);
        self::assertSame('https://api.test/createTask', $this->api->requests[0]['url']);
        self::assertSame([
            'clientKey' => 'KEY',
            'task' => ['type' => 'RecaptchaV2Task', 'websiteURL' => 'https://example.com', 'websiteKey' => 'SITEKEY'],
            'softId' => ClientOptions::DEFAULT_SOFT_ID,
        ], $this->api->bodies('createTask')[0]);
        self::assertSame([['clientKey' => 'KEY', 'taskId' => 42], ['clientKey' => 'KEY', 'taskId' => 42]], $this->api->bodies('getTaskResult'));
    }

    public function testCreateTaskApiErrorIsReported(): void
    {
        $this->api->onJson('createTask', ['errorId' => 1, 'errorCode' => 'ERROR_KEY_DOES_NOT_EXIST', 'errorDescription' => 'bad key']);

        try {
            $this->client()->solve(self::request());
            self::fail('no exception');
        } catch (CreateTaskException $e) {
            self::assertSame('ERROR_KEY_DOES_NOT_EXIST', $e->errorCode);
            self::assertSame('bad key', $e->errorDescription);
            self::assertStringContainsString('ERROR_KEY_DOES_NOT_EXIST', $e->getMessage());
        }
    }

    public function testErrorJsonWithHttpErrorStatusIsAnApiError(): void
    {
        $this->api->onJson('createTask', ['errorId' => 1, 'errorCode' => 'ERROR_ZERO_BALANCE', 'errorDescription' => 'no money'], 400);

        $this->expectException(CreateTaskException::class);
        $this->expectExceptionMessage('ERROR_ZERO_BALANCE');
        $this->client()->createTask(self::request());
    }

    public function testGetResultApiErrorIsReported(): void
    {
        $this->api
            ->onJson('createTask', ['errorId' => 0, 'taskId' => 1])
            ->onJson('getTaskResult', ['errorId' => 12, 'errorCode' => 'ERROR_CAPTCHA_UNSOLVABLE', 'errorDescription' => 'nope']);

        $this->expectException(GetResultException::class);
        $this->expectExceptionMessage('ERROR_CAPTCHA_UNSOLVABLE');
        $this->client()->solve(self::request());
    }

    public function testHttp500And503WhilePollingMeanStillProcessing(): void
    {
        $this->api
            ->on('getTaskResult', new HttpResponse(500, 'Internal Server Error'))
            ->on('getTaskResult', new HttpResponse(503, ''));

        self::assertFalse($this->client()->getTaskResult(7)->isReady);
        self::assertFalse($this->client()->getTaskResult(7)->isReady);
    }

    public function testOtherHttpStatusIsAnError(): void
    {
        $this->api->on('createTask', new HttpResponse(502, '<html>bad gateway</html>'));

        try {
            $this->client()->createTask(self::request());
            self::fail('no exception');
        } catch (UnexpectedHttpStatusException $e) {
            self::assertSame(502, $e->status);
            self::assertSame('createTask', $e->method);
        }
    }

    public function testPollingGivesUpWithTimeout(): void
    {
        $this->api
            ->onJson('createTask', ['errorId' => 0, 'taskId' => 5])
            ->onJson('getTaskResult', ['errorId' => 0, 'status' => 'processing']);

        $started = microtime(true);
        try {
            $this->client(0.1)->solve(self::request());
            self::fail('no exception');
        } catch (TimeoutException $e) {
            self::assertSame(5, $e->taskId);
        }
        self::assertLessThan(1.0, microtime(true) - $started);
    }

    public function testInvalidRequestIsRejectedBeforeAnyHttpCall(): void
    {
        try {
            $this->client()->solve(new RecaptchaV2Request('', 'K'));
            self::fail('no exception');
        } catch (ValidationException) {
        }
        self::assertSame([], $this->api->requests);
    }

    public function testGetBalance(): void
    {
        $this->api->onJson('getBalance', ['errorId' => 0, 'balance' => 12.5]);

        self::assertSame(12.5, $this->client()->getBalance());
        self::assertSame([['clientKey' => 'KEY']], $this->api->bodies('getBalance'));
    }

    public function testGetBalanceError(): void
    {
        $this->api->onJson('getBalance', ['errorId' => 1, 'errorCode' => 'ERROR_KEY_DOES_NOT_EXIST', 'errorDescription' => 'bad key']);

        $this->expectException(GetBalanceException::class);
        $this->client()->getBalance();
    }

    public function testMalformedResponse(): void
    {
        $this->api->on('getBalance', new HttpResponse(200, 'not json'));

        $this->expectException(MalformedResponseException::class);
        $this->client()->getBalance();
    }

    public function testTransportErrorsPropagateAndShareTheBaseClass(): void
    {
        $this->api->on('getBalance', new TransportException('curl error 28: timeout'));

        $this->expectException(CapMonsterException::class);
        $this->expectExceptionMessage('curl error 28');
        $this->client()->getBalance();
    }

    public function testDefaultScheduleComesFromTheRequest(): void
    {
        $this->api
            ->onJson('createTask', ['errorId' => 0, 'taskId' => 1])
            ->onJson('getTaskResult', ['errorId' => 0, 'status' => 'ready', 'solution' => ['text' => 'abc']]);
        $client = new CapMonsterClient(new ClientOptions('KEY'), $this->api);

        // ImageToText: first poll after 0.35 s
        $started = microtime(true);
        self::assertSame(['text' => 'abc'], $client->solve(new \Zennolab\CapMonsterCloud\Requests\ImageToTextRequest('QUJD')));
        self::assertGreaterThanOrEqual(0.3, microtime(true) - $started);
        self::assertSame('https://api.capmonster.cloud/createTask', $this->api->requests[0]['url']);
    }
}
