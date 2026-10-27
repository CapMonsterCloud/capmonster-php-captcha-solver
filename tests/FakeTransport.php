<?php

declare(strict_types=1);

namespace Zennolab\CapMonsterCloud\Tests;

use Zennolab\CapMonsterCloud\Exception\TransportException;
use Zennolab\CapMonsterCloud\Http\HttpResponse;
use Zennolab\CapMonsterCloud\Http\HttpTransport;

/** Replays canned responses per API method and records every request. */
final class FakeTransport implements HttpTransport
{
    /** @var list<array{url: string, body: array}> */
    public array $requests = [];

    /** @var array<string, list<HttpResponse|TransportException>> */
    private array $queues = [];

    /** Queues a response for a method; the last one of a method is repeated forever. */
    public function on(string $method, HttpResponse|TransportException $response): self
    {
        $this->queues[$method][] = $response;

        return $this;
    }

    public function onJson(string $method, array $json, int $status = 200): self
    {
        return $this->on($method, new HttpResponse($status, json_encode($json, JSON_THROW_ON_ERROR)));
    }

    public function post(string $url, array $body): HttpResponse
    {
        $this->requests[] = ['url' => $url, 'body' => $body];
        $method = substr($url, strrpos($url, '/') + 1);
        $queue = &$this->queues[$method];
        if (empty($queue)) {
            throw new \LogicException("no fake response for {$method}");
        }
        $response = count($queue) > 1 ? array_shift($queue) : $queue[0];
        if ($response instanceof TransportException) {
            throw $response;
        }

        return $response;
    }

    /** @return list<array> bodies sent to one method */
    public function bodies(string $method): array
    {
        return array_values(array_map(
            static fn ($r) => $r['body'],
            array_filter($this->requests, static fn ($r) => str_ends_with($r['url'], '/' . $method)),
        ));
    }
}
