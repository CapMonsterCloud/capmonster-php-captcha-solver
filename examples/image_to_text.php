<?php

// Usage: CAPMONSTER_API_KEY=... php examples/image_to_text.php [image file, default examples/images/sample.jpg]
require __DIR__ . '/../vendor/autoload.php';

use Zennolab\CapMonsterCloud\CapMonsterClient;
use Zennolab\CapMonsterCloud\ClientOptions;
use Zennolab\CapMonsterCloud\Requests\ImageToTextRequest;
use Zennolab\CapMonsterCloud\Requests\TextModule;

$client = new CapMonsterClient(new ClientOptions(getenv('CAPMONSTER_API_KEY') ?: 'YOUR_API_KEY'));

$solution = $client->solve(ImageToTextRequest::fromFile(
    $argv[1] ?? __DIR__ . '/images/sample.jpg',
    capMonsterModule: TextModule::UNIVERSAL,
    recognizingThreshold: 50,
    case: true,
));
print_r($solution);
