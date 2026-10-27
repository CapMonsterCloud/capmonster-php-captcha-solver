<?php

// Usage: CAPMONSTER_API_KEY=... php examples/friendly.php
require __DIR__ . '/../vendor/autoload.php';

use Zennolab\CapMonsterCloud\CapMonsterClient;
use Zennolab\CapMonsterCloud\ClientOptions;
use Zennolab\CapMonsterCloud\Requests\FriendlyCustomTaskRequest;

$client = new CapMonsterClient(new ClientOptions(getenv('CAPMONSTER_API_KEY') ?: 'YOUR_API_KEY'));

$solution = $client->solve(new FriendlyCustomTaskRequest(
    'https://www.example.com',
    'FCMSITEKEY000000000000000000000000000000',
    apiGetLib: 'https://cdn.jsdelivr.net/npm/friendly-challenge@0.9.18/widget.module.min.js',
    userAgent: 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/126.0.0.0 Safari/537.36',
));
print_r($solution);
