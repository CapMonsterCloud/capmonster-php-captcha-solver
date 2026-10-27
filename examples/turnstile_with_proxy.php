<?php

// Usage: CAPMONSTER_API_KEY=... php examples/turnstile_with_proxy.php
require __DIR__ . '/../vendor/autoload.php';

use Zennolab\CapMonsterCloud\CapMonsterClient;
use Zennolab\CapMonsterCloud\ClientOptions;
use Zennolab\CapMonsterCloud\ProxyInfo;
use Zennolab\CapMonsterCloud\ProxyType;
use Zennolab\CapMonsterCloud\Requests\TurnstileRequest;

$client = new CapMonsterClient(new ClientOptions(getenv('CAPMONSTER_API_KEY') ?: 'YOUR_API_KEY'));

// Use the same proxy in your own scraping session and in the task.
$proxy = new ProxyInfo(ProxyType::Http, '203.0.113.10', 8080, 'login', 'password');

$solution = $client->solve(TurnstileRequest::standard(
    'https://target-website.com/login',
    '0x4AAAAAAABnPIDnK2k_e-2',
    proxy: $proxy,
));
// put $solution['token'] into the cf-turnstile-response field and submit the form
echo 'Turnstile token: ', $solution['token'], "\n";
