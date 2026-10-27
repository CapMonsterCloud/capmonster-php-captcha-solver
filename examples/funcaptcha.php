<?php

// Usage: CAPMONSTER_API_KEY=... php examples/funcaptcha.php
require __DIR__ . '/../vendor/autoload.php';

use CapMonsterCloud\CapMonsterClient;
use CapMonsterCloud\ClientOptions;
use CapMonsterCloud\ProxyInfo;
use CapMonsterCloud\ProxyType;
use CapMonsterCloud\Requests\FuncaptchaRequest;

$client = new CapMonsterClient(new ClientOptions(getenv('CAPMONSTER_API_KEY') ?: 'YOUR_API_KEY'));

$proxy = new ProxyInfo(ProxyType::Http, '203.0.113.10', 8080, 'login', 'password'); // replace with your proxy

$solution = $client->solve(new FuncaptchaRequest(
    'https://example.com/',
    '69A21A01-CC7B-B9C6-0F9A-E7FA06677FFC',
    $proxy,
    funcaptchaApiJsSubdomain: 'example-api.arkoselabs.com',
));
print_r($solution);
