<?php

declare(strict_types=1);

namespace Zennolab\CapMonsterCloud\Http;

use Zennolab\CapMonsterCloud\Exception\TransportException;

/**
 * Sends one JSON POST request. The default implementation is {@see CurlTransport};
 * pass your own to CapMonsterClient to use another HTTP stack or to mock the API in tests.
 */
interface HttpTransport
{
    /**
     * @param array<string, mixed> $body Encoded as JSON.
     *
     * @throws TransportException on network-level failures. Non-2xx statuses are NOT errors here.
     */
    public function post(string $url, array $body): HttpResponse;
}
