<?php

// Usage: CAPMONSTER_API_KEY=... php examples/turnstile.php
require __DIR__ . '/../vendor/autoload.php';

use CapMonsterCloud\CapMonsterClient;
use CapMonsterCloud\ClientOptions;
use CapMonsterCloud\Requests\TurnstileRequest;

$client = new CapMonsterClient(new ClientOptions(getenv('CAPMONSTER_API_KEY') ?: 'YOUR_API_KEY'));

$solution = $client->solve(TurnstileRequest::standard('http://tsmanaged.zlsupport.com/', '0x4AAAAAAABUYP0XeMJF0xoy'));
print_r($solution);
