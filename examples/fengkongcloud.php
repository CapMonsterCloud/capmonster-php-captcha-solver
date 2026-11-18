<?php

// Usage: CAPMONSTER_API_KEY=... php examples/fengkongcloud.php
require __DIR__ . '/../vendor/autoload.php';

use CapMonsterCloud\CapMonsterClient;
use CapMonsterCloud\ClientOptions;
use CapMonsterCloud\Requests\FengkongCloudCustomTaskRequest;

$client = new CapMonsterClient(new ClientOptions(getenv('CAPMONSTER_API_KEY') ?: 'YOUR_API_KEY'));

$solution = $client->solve(new FengkongCloudCustomTaskRequest(
    'https://example.com/',
    'your-website-key',
    apiJsUrl: 'https://example.com/path/smcp.min.js',
));
print_r($solution);
