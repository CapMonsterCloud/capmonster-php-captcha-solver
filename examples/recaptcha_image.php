<?php

// Usage: CAPMONSTER_API_KEY=... php examples/recaptcha_image.php
require __DIR__ . '/../vendor/autoload.php';

use Zennolab\CapMonsterCloud\CapMonsterClient;
use Zennolab\CapMonsterCloud\ClientOptions;
use Zennolab\CapMonsterCloud\Requests\RecaptchaComplexImageTaskRequest;
use Zennolab\CapMonsterCloud\Requests\RecaptchaGrid;

$client = new CapMonsterClient(new ClientOptions(getenv('CAPMONSTER_API_KEY') ?: 'YOUR_API_KEY'));

$solution = $client->solve(new RecaptchaComplexImageTaskRequest(
    RecaptchaGrid::Grid3x3,
    task: 'Click on traffic lights',
    taskDefinition: '/m/015qff',
    imageUrls: ['https://i.postimg.cc/yYjg75Kv/payloadtraffic.jpg'],
    userAgent: 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/126.0.0.0 Safari/537.36',
));
print_r($solution);
