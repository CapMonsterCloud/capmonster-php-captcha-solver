<?php

// Usage: CAPMONSTER_API_KEY=... php examples/altcha.php
require __DIR__ . '/../vendor/autoload.php';

use CapMonsterCloud\CapMonsterClient;
use CapMonsterCloud\ClientOptions;
use CapMonsterCloud\Requests\AltchaCustomTaskRequest;

$client = new CapMonsterClient(new ClientOptions(getenv('CAPMONSTER_API_KEY') ?: 'YOUR_API_KEY'));

$solution = $client->solve(new AltchaCustomTaskRequest(
    'https://example.com/login',
    'altcha-public-key-123',
    challenge: 'challenge_string_here',
    iterations: '1000',
    salt: 'salt_string_here',
    signature: 'signature_string_here',
    userAgent: 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/126.0.0.0 Safari/537.36',
));
print_r($solution);
