<?php

// Usage: CAPMONSTER_API_KEY=... php examples/yidun.php
require __DIR__ . '/../vendor/autoload.php';

use Zennolab\CapMonsterCloud\CapMonsterClient;
use Zennolab\CapMonsterCloud\ClientOptions;
use Zennolab\CapMonsterCloud\Requests\YidunRequest;

$client = new CapMonsterClient(new ClientOptions(getenv('CAPMONSTER_API_KEY') ?: 'YOUR_API_KEY'));

$solution = $client->solve(new YidunRequest('https://example.com/', '6cw0f0485d5d46auacf9b735d20218a5'));
print_r($solution);
