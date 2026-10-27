<?php

// Usage: CAPMONSTER_API_KEY=... php examples/tspd.php
require __DIR__ . '/../vendor/autoload.php';

use CapMonsterCloud\CapMonsterClient;
use CapMonsterCloud\ClientOptions;
use CapMonsterCloud\ProxyInfo;
use CapMonsterCloud\ProxyType;
use CapMonsterCloud\Requests\TspdCustomTaskRequest;

$client = new CapMonsterClient(new ClientOptions(getenv('CAPMONSTER_API_KEY') ?: 'YOUR_API_KEY'));

$proxy = new ProxyInfo(ProxyType::Http, '203.0.113.10', 8080, 'login', 'password'); // replace with your proxy

$solution = $client->solve(new TspdCustomTaskRequest(
    'https://example.com/',
    tspdCookie: 'TS386a400d029=08267010245; TS386a400d078=08267dbb3b0c',
    htmlPageBase64: 'PCFET0NUWVBFIGh0bWw+PC9odG1sPg==', // the whole TSPD challenge page in base64
    proxy: $proxy,
    userAgent: 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/126.0.0.0 Safari/537.36',
));
print_r($solution);
