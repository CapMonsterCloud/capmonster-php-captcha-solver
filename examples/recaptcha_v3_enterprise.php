<?php

// Usage: CAPMONSTER_API_KEY=... php examples/recaptcha_v3_enterprise.php
require __DIR__ . '/../vendor/autoload.php';

use Zennolab\CapMonsterCloud\CapMonsterClient;
use Zennolab\CapMonsterCloud\ClientOptions;
use Zennolab\CapMonsterCloud\Requests\RecaptchaV3EnterpriseRequest;

$client = new CapMonsterClient(new ClientOptions(getenv('CAPMONSTER_API_KEY') ?: 'YOUR_API_KEY'));

$solution = $client->solve(new RecaptchaV3EnterpriseRequest(
    'https://lessons.zennolab.com/captchas/recaptcha/v3.php?level=beta',
    '6Le0xVgUAAAAAIt20XEB4rVhYOODgTl00d8juDob',
    minScore: 0.9,
    pageAction: 'submit',
));
print_r($solution);
