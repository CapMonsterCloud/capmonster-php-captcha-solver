<?php

// Usage: CAPMONSTER_API_KEY=... php examples/hunt.php
require __DIR__ . '/../vendor/autoload.php';

use Zennolab\CapMonsterCloud\CapMonsterClient;
use Zennolab\CapMonsterCloud\ClientOptions;
use Zennolab\CapMonsterCloud\ProxyInfo;
use Zennolab\CapMonsterCloud\ProxyType;
use Zennolab\CapMonsterCloud\Requests\HuntCustomTaskRequest;

$client = new CapMonsterClient(new ClientOptions(getenv('CAPMONSTER_API_KEY') ?: 'YOUR_API_KEY'));

$proxy = new ProxyInfo(ProxyType::Http, '203.0.113.10', 8080, 'login', 'password'); // replace with your proxy

$solution = $client->solve(HuntCustomTaskRequest::fingerprint(
    'https://example.com/',
    'https://example.com/hd-api/external/apps/a2157wab1045d68672a63557e0n2a77edbfd15ea/api.js',
    $proxy,
    userAgent: 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/126.0.0.0 Safari/537.36',
));
print_r($solution);
