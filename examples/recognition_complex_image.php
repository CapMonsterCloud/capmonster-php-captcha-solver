<?php

// Usage: CAPMONSTER_API_KEY=... php examples/recognition_complex_image.php <task> <image>... (e.g. oocl_rotate_double_new bg.png ring.png circle.png)
require __DIR__ . '/../vendor/autoload.php';

use CapMonsterCloud\CapMonsterClient;
use CapMonsterCloud\ClientOptions;
use CapMonsterCloud\Requests\RecognitionComplexImageTaskRequest;

if ($argc < 3) {
    fwrite(STDERR, "usage: php examples/recognition_complex_image.php <task> <image>...\n");
    exit(1);
}
[$task, $images] = [$argv[1], array_slice($argv, 2)];

$client = new CapMonsterClient(new ClientOptions(getenv('CAPMONSTER_API_KEY') ?: 'YOUR_API_KEY'));

$solution = $client->solve(new RecognitionComplexImageTaskRequest(
    array_map(static fn (string $path): string => base64_encode((string) file_get_contents($path)), $images),
    $task,
));
print_r($solution);
