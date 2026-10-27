<?php

declare(strict_types=1);

namespace Zennolab\CapMonsterCloud;

use Zennolab\CapMonsterCloud\Exception\ValidationException;

/**
 * Proxy used by CapMonster workers to open the captcha page.
 *
 * It is flattened into the task as `proxyType`, `proxyAddress`, `proxyPort`, `proxyLogin`, `proxyPassword`.
 */
final class ProxyInfo
{
    /**
     * @param string $address IPv4/IPv6 address or hostname (not a local/transparent proxy).
     */
    public function __construct(
        public readonly ProxyType $type,
        public readonly string $address,
        public readonly int $port,
        public readonly ?string $login = null,
        public readonly ?string $password = null,
    ) {
        if ($address === '') {
            throw new ValidationException('proxy address must not be empty');
        }
        if ($port < 1 || $port > 65535) {
            throw new ValidationException("proxy port must be between 1 and 65535, got {$port}");
        }
    }

    /** @return array<string, string|int> */
    public function toArray(): array
    {
        return array_filter([
            'proxyType' => $this->type->value,
            'proxyAddress' => $this->address,
            'proxyPort' => $this->port,
            'proxyLogin' => $this->login,
            'proxyPassword' => $this->password,
        ], static fn ($v) => $v !== null);
    }

    /** Keeps the password out of var_dump()/print_r() output. */
    public function __debugInfo(): array
    {
        return [
            'type' => $this->type,
            'address' => $this->address,
            'port' => $this->port,
            'login' => $this->login,
            'password' => $this->password === null ? null : '***',
        ];
    }
}
