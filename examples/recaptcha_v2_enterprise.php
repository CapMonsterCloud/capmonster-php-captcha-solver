<?php

// Usage: CAPMONSTER_API_KEY=... php examples/recaptcha_v2_enterprise.php
require __DIR__ . '/../vendor/autoload.php';

use Zennolab\CapMonsterCloud\CapMonsterClient;
use Zennolab\CapMonsterCloud\ClientOptions;
use Zennolab\CapMonsterCloud\Requests\RecaptchaV2EnterpriseRequest;

$client = new CapMonsterClient(new ClientOptions(getenv('CAPMONSTER_API_KEY') ?: 'YOUR_API_KEY'));

$solution = $client->solve(new RecaptchaV2EnterpriseRequest(
    'https://example.com/',
    '6Lf26sUnAAAAAIKLuWNYgRsFUfmI-3Lex3xT5N-s',
    enterprisePayload: ['s' => 'SOME_ADDITIONAL_TOKEN'],
));
print_r($solution);
