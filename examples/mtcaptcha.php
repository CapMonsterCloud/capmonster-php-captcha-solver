<?php

// Usage: CAPMONSTER_API_KEY=... php examples/mtcaptcha.php
require __DIR__ . '/../vendor/autoload.php';

use CapMonsterCloud\CapMonsterClient;
use CapMonsterCloud\ClientOptions;
use CapMonsterCloud\Requests\MtCaptchaRequest;

$client = new CapMonsterClient(new ClientOptions(getenv('CAPMONSTER_API_KEY') ?: 'YOUR_API_KEY'));

$solution = $client->solve(new MtCaptchaRequest('https://example.com/', 'MTPublic-abCDEFJAB'));
print_r($solution);
