<?php

// Usage: CAPMONSTER_API_KEY=... php examples/balance.php
require __DIR__ . '/../vendor/autoload.php';

use CapMonsterCloud\CapMonsterClient;
use CapMonsterCloud\ClientOptions;

$client = new CapMonsterClient(new ClientOptions(getenv('CAPMONSTER_API_KEY') ?: 'YOUR_API_KEY'));

printf("Balance: %.4f USD\n", $client->getBalance());
