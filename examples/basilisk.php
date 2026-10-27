<?php

// Usage: CAPMONSTER_API_KEY=... php examples/basilisk.php
require __DIR__ . '/../vendor/autoload.php';

use Zennolab\CapMonsterCloud\CapMonsterClient;
use Zennolab\CapMonsterCloud\ClientOptions;
use Zennolab\CapMonsterCloud\Requests\BasiliskCustomTaskRequest;

$client = new CapMonsterClient(new ClientOptions(getenv('CAPMONSTER_API_KEY') ?: 'YOUR_API_KEY'));

$solution = $client->solve(new BasiliskCustomTaskRequest('https://example.com/account/register', 'b7890hre5cf2544b2759c19fb2600897'));
print_r($solution);
