<?php

// Usage: CAPMONSTER_API_KEY=... php examples/imperva.php
require __DIR__ . '/../vendor/autoload.php';

use Zennolab\CapMonsterCloud\CapMonsterClient;
use Zennolab\CapMonsterCloud\ClientOptions;
use Zennolab\CapMonsterCloud\ProxyInfo;
use Zennolab\CapMonsterCloud\ProxyType;
use Zennolab\CapMonsterCloud\Requests\ImpervaCustomTaskRequest;

$client = new CapMonsterClient(new ClientOptions(getenv('CAPMONSTER_API_KEY') ?: 'YOUR_API_KEY'));

$proxy = new ProxyInfo(ProxyType::Http, '203.0.113.10', 8080, 'login', 'password'); // replace with your proxy

$solution = $client->solve(new ImpervaCustomTaskRequest(
    'https://example.com/login',
    incapsulaScriptUrl: '_Incapsula_Resource?SWJIYLWA=719d34d31c8e3a6e6fffd425f7e032f3',
    incapsulaCookies: 'incap_ses_1166_2930313=br7iX33ZNCtf3HlpEXcuEDzz72cAAAAA0suDnBGrq/iA0J4oERYzjQ==; visid_incap_2930313=P3hgPVm9S8Oond1L0sXhZqfK72cAAAAAQUIPAAAAAABoMSY9xZ34RvRseJRiY6s+;',
    proxy: $proxy,
    reese84UrlEndpoint: 'Built-with-the-For-hopence-Hurleysurfecting-the-',
    userAgent: 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/126.0.0.0 Safari/537.36',
));
print_r($solution);
