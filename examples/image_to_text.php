<?php

// Usage: CAPMONSTER_API_KEY=... php examples/image_to_text.php path/to/captcha.png
require __DIR__ . '/../vendor/autoload.php';

use Zennolab\CapMonsterCloud\CapMonsterClient;
use Zennolab\CapMonsterCloud\ClientOptions;
use Zennolab\CapMonsterCloud\Requests\ImageToTextRequest;
use Zennolab\CapMonsterCloud\Requests\TextModule;

$client = new CapMonsterClient(new ClientOptions(getenv('CAPMONSTER_API_KEY') ?: 'YOUR_API_KEY'));

$request = ImageToTextRequest::fromFile($argv[1] ?? 'captcha.png', capMonsterModule: TextModule::UNIVERSAL);
echo 'Text: ', $client->solve($request)['text'], "\n";
