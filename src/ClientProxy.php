<?php

declare(strict_types=1);

namespace CapMonsterCloud;

use CapMonsterCloud\Exception\ValidationException;

/** Proxy through which the SDK itself sends its HTTP requests to the CapMonster Cloud API. */
final class ClientProxy
{
    public function __construct(
        public readonly ProxyType $type,
        public readonly string $address,
        public readonly int $port,
        public readonly ?string $login = null,
        public readonly ?string $password = null,
    ) {
        if ($address === '') {
            throw new ValidationException('client proxy address must not be empty');
        }
        if ($port < 1 || $port > 65535) {
            throw new ValidationException("client proxy port must be between 1 and 65535, got {$port}");
        }
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
