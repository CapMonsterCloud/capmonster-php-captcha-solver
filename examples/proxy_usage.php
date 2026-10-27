<?php

// Usage: CAPMONSTER_API_KEY=... php examples/proxy_usage.php
// The proxy is passed to CapMonster Cloud and used by the solver; use the same proxy in your own session.
require __DIR__ . '/../vendor/autoload.php';

use Zennolab\CapMonsterCloud\CapMonsterClient;
use Zennolab\CapMonsterCloud\ClientOptions;
use Zennolab\CapMonsterCloud\ProxyInfo;
use Zennolab\CapMonsterCloud\ProxyType;
use Zennolab\CapMonsterCloud\Requests\RecaptchaV2Request;

$client = new CapMonsterClient(new ClientOptions(getenv('CAPMONSTER_API_KEY') ?: 'YOUR_API_KEY'));

$proxy = new ProxyInfo(ProxyType::Http, '203.0.113.10', 8080, 'login', 'password'); // replace with your proxy

$solution = $client->solve(new RecaptchaV2Request(
    'https://lessons.zennolab.com/captchas/recaptcha/v2_simple.php?level=high',
    '6Lcg7CMUAAAAANphynKgn9YAgA4tQ2KI_iqRyTwd',
    proxy: $proxy,
));
print_r($solution);
