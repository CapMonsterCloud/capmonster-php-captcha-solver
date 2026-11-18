<?php

// Usage: CAPMONSTER_API_KEY=... php examples/proton.php
require __DIR__ . '/../vendor/autoload.php';

use CapMonsterCloud\CapMonsterClient;
use CapMonsterCloud\ClientOptions;
use CapMonsterCloud\ProxyInfo;
use CapMonsterCloud\ProxyType;
use CapMonsterCloud\Requests\ProtonCustomTaskRequest;

$client = new CapMonsterClient(new ClientOptions(getenv('CAPMONSTER_API_KEY') ?: 'YOUR_API_KEY'));

$proxy = new ProxyInfo(ProxyType::Http, '203.0.113.10', 8080, 'login', 'password'); // replace with your proxy

$solution = $client->solve(new ProtonCustomTaskRequest(
    'https://example.com/',
    humanVerificationToken: 'your-verification-token',
    pageAction: ProtonCustomTaskRequest::ACTION_LOGIN,
    proxy: $proxy,
));
print_r($solution);
