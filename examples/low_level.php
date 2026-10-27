<?php

// createTask + getTaskResult by hand, e.g. to poll from your own job queue instead of blocking in solve().
require __DIR__ . '/../vendor/autoload.php';

use Zennolab\CapMonsterCloud\CapMonsterClient;
use Zennolab\CapMonsterCloud\ClientOptions;
use Zennolab\CapMonsterCloud\Requests\GeetestRequest;

$client = new CapMonsterClient(new ClientOptions(getenv('CAPMONSTER_API_KEY') ?: 'YOUR_API_KEY'));

$request = GeetestRequest::v4('https://example.com/login', 'YOUR_CAPTCHA_ID', ['riskType' => 'slide']);
$taskId = $client->createTask($request);

$schedule = $request->timeouts();
usleep((int) ($schedule->firstRequestDelay * 1e6));
for ($waited = 0.0; $waited < $schedule->timeout; $waited += $schedule->requestsInterval) {
    $result = $client->getTaskResult($taskId);
    if ($result->isReady) {
        print_r($result->solution);
        exit(0);
    }
    usleep((int) ($schedule->requestsInterval * 1e6));
}
fwrite(STDERR, "timeout\n");
exit(1);
