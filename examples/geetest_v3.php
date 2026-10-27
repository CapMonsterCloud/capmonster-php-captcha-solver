<?php

// Usage: CAPMONSTER_API_KEY=... php examples/geetest_v3.php
require __DIR__ . '/../vendor/autoload.php';

use Zennolab\CapMonsterCloud\CapMonsterClient;
use Zennolab\CapMonsterCloud\ClientOptions;
use Zennolab\CapMonsterCloud\Requests\GeetestRequest;

$client = new CapMonsterClient(new ClientOptions(getenv('CAPMONSTER_API_KEY') ?: 'YOUR_API_KEY'));

$solution = $client->solve(GeetestRequest::v3(
    'https://example.com/',
    '81388ea1fc187e0c335c0a8907ff2625',
    '2e2f0f65240058b683cb6ea21c303eea6n', // fresh challenge, valid for one solve only
));
print_r($solution);
