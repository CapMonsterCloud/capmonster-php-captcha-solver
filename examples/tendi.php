<?php

// Usage: CAPMONSTER_API_KEY=... php examples/tendi.php
require __DIR__ . '/../vendor/autoload.php';

use CapMonsterCloud\CapMonsterClient;
use CapMonsterCloud\ClientOptions;
use CapMonsterCloud\Requests\TenDiCustomTaskRequest;

$client = new CapMonsterClient(new ClientOptions(getenv('CAPMONSTER_API_KEY') ?: 'YOUR_API_KEY'));

$solution = $client->solve(new TenDiCustomTaskRequest('https://example.com/', '189123456'));
print_r($solution);
