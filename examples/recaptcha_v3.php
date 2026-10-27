<?php

// Usage: CAPMONSTER_API_KEY=... php examples/recaptcha_v3.php
require __DIR__ . '/../vendor/autoload.php';

use CapMonsterCloud\CapMonsterClient;
use CapMonsterCloud\ClientOptions;
use CapMonsterCloud\Requests\RecaptchaV3ProxylessRequest;

$client = new CapMonsterClient(new ClientOptions(getenv('CAPMONSTER_API_KEY') ?: 'YOUR_API_KEY'));

$solution = $client->solve(new RecaptchaV3ProxylessRequest(
    'https://lessons.zennolab.com/captchas/recaptcha/v3.php?level=beta',
    '6Le0xVgUAAAAAIt20XEB4rVhYOODgTl00d8juDob',
    minScore: 0.9,
));
print_r($solution);
