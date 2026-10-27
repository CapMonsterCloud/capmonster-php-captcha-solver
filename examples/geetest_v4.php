<?php

// Usage: CAPMONSTER_API_KEY=... php examples/geetest_v4.php
require __DIR__ . '/../vendor/autoload.php';

use Zennolab\CapMonsterCloud\CapMonsterClient;
use Zennolab\CapMonsterCloud\ClientOptions;
use Zennolab\CapMonsterCloud\Requests\GeetestRequest;

$client = new CapMonsterClient(new ClientOptions(getenv('CAPMONSTER_API_KEY') ?: 'YOUR_API_KEY'));

$solution = $client->solve(GeetestRequest::v4(
    'https://faucetpay.io/account/login',
    '4eb8b0c2b27f3365b9244d9da81638c6',
    initParameters: ['riskType' => 'slide'],
));
print_r($solution);
