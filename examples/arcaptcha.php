<?php

// Usage: CAPMONSTER_API_KEY=... php examples/arcaptcha.php
require __DIR__ . '/../vendor/autoload.php';

use CapMonsterCloud\CapMonsterClient;
use CapMonsterCloud\ClientOptions;
use CapMonsterCloud\Requests\ArCaptchaRequest;

$client = new CapMonsterClient(new ClientOptions(getenv('CAPMONSTER_API_KEY') ?: 'YOUR_API_KEY'));

$solution = $client->solve(new ArCaptchaRequest(
    'https://example.com/',
    'your-website-key',
    isInvisible: false,
));
print_r($solution);
