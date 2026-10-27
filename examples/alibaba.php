<?php

// Usage: CAPMONSTER_API_KEY=... php examples/alibaba.php
require __DIR__ . '/../vendor/autoload.php';

use CapMonsterCloud\CapMonsterClient;
use CapMonsterCloud\ClientOptions;
use CapMonsterCloud\Requests\AlibabaCustomTaskRequest;

$client = new CapMonsterClient(new ClientOptions(getenv('CAPMONSTER_API_KEY') ?: 'YOUR_API_KEY'));

$solution = $client->solve(new AlibabaCustomTaskRequest(
    'https://www.example.com',
    sceneId: '1ww7426c',
    prefix: 'dlw3kug',
    userAgent: 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/126.0.0.0 Safari/537.36',
    apiGetLib: 'https://o.example.com/captcha-frontend/aliyunCaptcha/AliyunCaptcha.js?t=2041',
));
print_r($solution);
