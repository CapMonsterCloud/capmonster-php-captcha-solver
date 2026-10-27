<?php

declare(strict_types=1);

namespace Zennolab\CapMonsterCloud\Http;

use Zennolab\CapMonsterCloud\ClientProxy;
use Zennolab\CapMonsterCloud\Exception\TransportException;
use Zennolab\CapMonsterCloud\ProxyType;

/** {@see HttpTransport} on top of ext-curl. One curl handle is reused, so keep-alive works across polls. */
final class CurlTransport implements HttpTransport
{
    private ?\CurlHandle $handle = null;

    public function __construct(
        private readonly string $userAgent,
        private readonly float $timeout = 20.0,
        private readonly ?ClientProxy $proxy = null,
    ) {
    }

    public function post(string $url, array $body): HttpResponse
    {
        try {
            $json = json_encode($body, JSON_THROW_ON_ERROR | JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);
        } catch (\JsonException $e) {
            throw new TransportException('cannot encode request body: ' . $e->getMessage(), 0, $e);
        }

        $this->handle ??= curl_init();
        $ch = $this->handle;
        curl_reset($ch);
        $options = [
            CURLOPT_URL => $url,
            CURLOPT_POST => true,
            CURLOPT_POSTFIELDS => $json,
            CURLOPT_HTTPHEADER => ['Content-Type: application/json', 'Accept: application/json'],
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_USERAGENT => $this->userAgent,
            CURLOPT_TIMEOUT_MS => (int) ($this->timeout * 1000),
            CURLOPT_CONNECTTIMEOUT_MS => (int) (min($this->timeout, 10.0) * 1000),
        ];
        if ($this->proxy !== null) {
            $options[CURLOPT_PROXY] = $this->proxy->address;
            $options[CURLOPT_PROXYPORT] = $this->proxy->port;
            $options[CURLOPT_PROXYTYPE] = match ($this->proxy->type) {
                ProxyType::Http => CURLPROXY_HTTP,
                ProxyType::Https => CURLPROXY_HTTPS,
                ProxyType::Socks4 => CURLPROXY_SOCKS4,
                ProxyType::Socks5 => CURLPROXY_SOCKS5_HOSTNAME,
            };
            if ($this->proxy->login !== null) {
                // separate options instead of PROXYUSERPWD: a ':' inside the login stays intact
                $options[CURLOPT_PROXYUSERNAME] = $this->proxy->login;
                $options[CURLOPT_PROXYPASSWORD] = $this->proxy->password ?? '';
            }
        }
        curl_setopt_array($ch, $options);

        $response = curl_exec($ch);
        if ($response === false) {
            throw new TransportException(sprintf('curl error %d: %s', curl_errno($ch), curl_error($ch)));
        }

        return new HttpResponse((int) curl_getinfo($ch, CURLINFO_RESPONSE_CODE), (string) $response);
    }
}
