<?php

// Usage: CAPMONSTER_API_KEY=... php examples/heytap.php
require __DIR__ . '/../vendor/autoload.php';

use CapMonsterCloud\CapMonsterClient;
use CapMonsterCloud\ClientOptions;
use CapMonsterCloud\Requests\HeyTapCustomTaskRequest;

$client = new CapMonsterClient(new ClientOptions(getenv('CAPMONSTER_API_KEY') ?: 'YOUR_API_KEY'));

$solution = $client->solve(new HeyTapCustomTaskRequest(
    'https://example.com/',
    'your-website-key',
    apiServer: 'api.example.com',
));
print_r($solution);
