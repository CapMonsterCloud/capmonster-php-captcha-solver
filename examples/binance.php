<?php

// Usage: CAPMONSTER_API_KEY=... php examples/binance.php
require __DIR__ . '/../vendor/autoload.php';

use CapMonsterCloud\CapMonsterClient;
use CapMonsterCloud\ClientOptions;
use CapMonsterCloud\Requests\BinanceTaskRequest;

$client = new CapMonsterClient(new ClientOptions(getenv('CAPMONSTER_API_KEY') ?: 'YOUR_API_KEY'));

$solution = $client->solve(new BinanceTaskRequest(
    'https://accounts.binance.com/ru/login?loginChannel=&return_to=',
    'login',
    '2b8137c0b9b44189800368819354e114',
));
print_r($solution);
