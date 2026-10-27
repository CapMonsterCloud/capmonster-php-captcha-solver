<?php

// Usage: CAPMONSTER_API_KEY=... php examples/amazon_waf.php
require __DIR__ . '/../vendor/autoload.php';

use CapMonsterCloud\CapMonsterClient;
use CapMonsterCloud\ClientOptions;
use CapMonsterCloud\Requests\AmazonWafRequest;

$client = new CapMonsterClient(new ClientOptions(getenv('CAPMONSTER_API_KEY') ?: 'YOUR_API_KEY'));

$solution = $client->solve(AmazonWafRequest::visibleCaptcha(
    'https://example.com/',
    'AQIDAHjcYu/GjX+QlghicBgQ/7bFaQZ+m5FKCMDnO+vTp9wjBQ2MNq0uv7gT8+IFxrGrLgsA',
    'https://234324vgvc23.yejk.captcha-sdk.awswaf.com/234324vgvc23/jsapi.js',
));
print_r($solution);
