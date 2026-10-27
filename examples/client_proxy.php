<?php

// Usage: CAPMONSTER_API_KEY=... php examples/client_proxy.php
// ClientProxy routes the SDK's own HTTP requests to api.capmonster.cloud through a proxy.
// It is not sent to the solver: to solve through a proxy, see proxy_usage.php.
require __DIR__ . '/../vendor/autoload.php';

use Zennolab\CapMonsterCloud\CapMonsterClient;
use Zennolab\CapMonsterCloud\ClientOptions;
use Zennolab\CapMonsterCloud\ClientProxy;
use Zennolab\CapMonsterCloud\ProxyType;

$client = new CapMonsterClient(new ClientOptions(
    getenv('CAPMONSTER_API_KEY') ?: 'YOUR_API_KEY',
    clientProxy: new ClientProxy(ProxyType::Http, '203.0.113.10', 8080, 'login', 'password'), // replace with your proxy
));

printf("Balance: %.4f USD\n", $client->getBalance());
