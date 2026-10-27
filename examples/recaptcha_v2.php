<?php

// Usage: CAPMONSTER_API_KEY=... php examples/recaptcha_v2.php
require __DIR__ . '/../vendor/autoload.php';

use Zennolab\CapMonsterCloud\CapMonsterClient;
use Zennolab\CapMonsterCloud\ClientOptions;
use Zennolab\CapMonsterCloud\Exception\CapMonsterException;
use Zennolab\CapMonsterCloud\Requests\RecaptchaV2Request;

$client = new CapMonsterClient(new ClientOptions(getenv('CAPMONSTER_API_KEY') ?: 'YOUR_API_KEY'));

try {
    printf("Balance: %.4f USD\n", $client->getBalance());

    $solution = $client->solve(new RecaptchaV2Request(
        'https://lessons.zennolab.com/captchas/recaptcha/v2_simple.php?level=high',
        '6Lcg7CMUAAAAANphynKgn9YAgA4tQ2KI_iqRyTwd',
    ));
    echo 'gRecaptchaResponse: ', $solution['gRecaptchaResponse'], "\n";
} catch (CapMonsterException $e) {
    fwrite(STDERR, get_class($e) . ': ' . $e->getMessage() . "\n");
    exit(1);
}
