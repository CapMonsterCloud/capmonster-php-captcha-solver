<?php

// Usage: CAPMONSTER_API_KEY=... php examples/turnstile_waitroom.php
require __DIR__ . '/../vendor/autoload.php';

use CapMonsterCloud\CapMonsterClient;
use CapMonsterCloud\ClientOptions;
use CapMonsterCloud\ProxyInfo;
use CapMonsterCloud\ProxyType;
use CapMonsterCloud\Requests\TurnstileRequest;

$client = new CapMonsterClient(new ClientOptions(getenv('CAPMONSTER_API_KEY') ?: 'YOUR_API_KEY'));

$proxy = new ProxyInfo(ProxyType::Http, '203.0.113.10', 8080, 'login', 'password'); // replace with your proxy

$solution = $client->solve(TurnstileRequest::waitRoom(
    'https://example.com/',
    '0x4AAAAAAADnPIDROrmt1Wwj',
    htmlPageBase64: 'PCFET0NUWVBFIGh0bWw+PC9odG1sPg==', // base64 of the waiting room page
    userAgent: 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/126.0.0.0 Safari/537.36',
    proxy: $proxy,
));
print_r($solution);
