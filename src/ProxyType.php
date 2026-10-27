<?php

declare(strict_types=1);

namespace CapMonsterCloud;

/** Protocol of a proxy server. */
enum ProxyType: string
{
    case Http = 'http';
    case Https = 'https';
    case Socks4 = 'socks4';
    case Socks5 = 'socks5';
}
