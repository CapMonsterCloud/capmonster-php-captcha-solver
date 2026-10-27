<?php

// Usage: CAPMONSTER_API_KEY=... php examples/datadome.php
require __DIR__ . '/../vendor/autoload.php';

use CapMonsterCloud\CapMonsterClient;
use CapMonsterCloud\ClientOptions;
use CapMonsterCloud\ProxyInfo;
use CapMonsterCloud\ProxyType;
use CapMonsterCloud\Requests\DataDomeCustomTaskRequest;

$client = new CapMonsterClient(new ClientOptions(getenv('CAPMONSTER_API_KEY') ?: 'YOUR_API_KEY'));

$proxy = new ProxyInfo(ProxyType::Http, '203.0.113.10', 8080, 'login', 'password'); // replace with your proxy

$solution = $client->solve(new DataDomeCustomTaskRequest(
    'https://antoinevastel.com/bots/datadome',
    captchaUrl: 'https://geo.captcha-delivery.com/captcha/?initialCid=AHrlqAAAAAMAJxx4dfgwjzwAQW0ctQ%3D%3D&hash=D66B23AC3F48A302A7654416846381&cid=d3k5rbDsu8cq0kmPHISS3hsC3f4qeL_K12~G33PrE4fbkmDYSul6l0Ze_aG5sUHLKG0676UpTv6GFvUgIActglZF33GTodOoRhEDkMMsuWTodlYa3YYQ9xKy9J89PAWh&t=fe&referer=https%3A%2F%2Fantoinevastel.com%2Fbots%2Fdatadome&s=21705&e=04fc682817ba89bf8fa4b18031fa53294fa0fb7449d95c036a1986413e6dfc7d',
    datadomeCookie: 'datadome=d3k5rbDsu8cq0kmPHISS3hsC3f4qeL_K12~G33PrE4fbkmDYSul6l0Ze_aG5sUHLKG0676UpTv6GFvUgIActglZF33GTodOoRhEDkMMsuWTodlYa3YYQ9xKy9J89PAWh',
    proxy: $proxy,
    userAgent: 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/126.0.0.0 Safari/537.36',
));
print_r($solution);
