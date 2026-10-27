<?php

declare(strict_types=1);

namespace CapMonsterCloud\Tests;

use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;
use CapMonsterCloud\Exception\ValidationException;
use CapMonsterCloud\ProxyInfo;
use CapMonsterCloud\ProxyType;
use CapMonsterCloud\Requests;
use CapMonsterCloud\Requests\CaptchaRequest;
use CapMonsterCloud\Requests\RecaptchaGrid;
use CapMonsterCloud\Requests\TextModule;

final class RequestsTest extends TestCase
{
    private const URL = 'https://example.com';

    private static function proxy(): ProxyInfo
    {
        return new ProxyInfo(ProxyType::Http, '1.2.3.4', 8080, 'u', 'p');
    }

    private const PROXY_JSON = [
        'proxyType' => 'http', 'proxyAddress' => '1.2.3.4', 'proxyPort' => 8080, 'proxyLogin' => 'u', 'proxyPassword' => 'p',
    ];

    /** @return iterable<string, array{CaptchaRequest, array}> */
    public static function tasks(): iterable
    {
        $u = self::URL;
        $p = self::PROXY_JSON;

        yield 'recaptcha v2 minimal' => [
            new Requests\RecaptchaV2Request($u, 'KEY'),
            ['type' => 'RecaptchaV2Task', 'websiteURL' => $u, 'websiteKey' => 'KEY'],
        ];
        yield 'recaptcha v2 full' => [
            new Requests\RecaptchaV2Request($u, 'KEY', self::proxy(), 'DS', 'UA', 'a=b', true),
            ['type' => 'RecaptchaV2Task', 'websiteURL' => $u, 'websiteKey' => 'KEY', 'recaptchaDataSValue' => 'DS',
                'userAgent' => 'UA', 'cookies' => 'a=b', 'isInvisible' => true] + $p,
        ];
        yield 'recaptcha v2 enterprise' => [
            new Requests\RecaptchaV2EnterpriseRequest($u, 'KEY', enterprisePayload: ['s' => 'S'], apiDomain: 'www.recaptcha.net', pageAction: 'login'),
            ['type' => 'RecaptchaV2EnterpriseTask', 'websiteURL' => $u, 'websiteKey' => 'KEY',
                'enterprisePayload' => ['s' => 'S'], 'apiDomain' => 'www.recaptcha.net', 'pageAction' => 'login'],
        ];
        yield 'recaptcha v3' => [
            new Requests\RecaptchaV3ProxylessRequest($u, 'KEY', minScore: 0.3, pageAction: 'verify', isEnterprise: false),
            ['type' => 'RecaptchaV3TaskProxyless', 'websiteURL' => $u, 'websiteKey' => 'KEY', 'minScore' => 0.3,
                'pageAction' => 'verify', 'isEnterprise' => false],
        ];
        yield 'recaptcha v3 enterprise' => [
            new Requests\RecaptchaV3EnterpriseRequest($u, 'KEY', 0.9),
            ['type' => 'RecaptchaV3EnterpriseTask', 'websiteURL' => $u, 'websiteKey' => 'KEY', 'minScore' => 0.9],
        ];
        yield 'recaptcha click by url' => [
            new Requests\RecaptchaComplexImageTaskRequest(RecaptchaGrid::Grid3x3, task: 'Click on traffic lights', imageUrls: ['https://x/img.jpg']),
            ['type' => 'ComplexImageTask', 'class' => 'recaptcha', 'imageUrls' => ['https://x/img.jpg'],
                'metadata' => ['Grid' => '3x3', 'Task' => 'Click on traffic lights']],
        ];
        yield 'recaptcha click by base64' => [
            new Requests\RecaptchaComplexImageTaskRequest(RecaptchaGrid::Grid1x1, taskDefinition: '/m/015qff', imagesBase64: ['QUJD'], userAgent: 'UA', websiteUrl: $u),
            ['type' => 'ComplexImageTask', 'class' => 'recaptcha', 'imagesBase64' => ['QUJD'],
                'metadata' => ['Grid' => '1x1', 'TaskDefinition' => '/m/015qff'], 'userAgent' => 'UA', 'websiteURL' => $u],
        ];
        yield 'recognition' => [
            new Requests\RecognitionComplexImageTaskRequest(['QUJD'], 'bills_audio', '123', Requests\RecognitionComplexImageTaskRequest::PAYLOAD_AUDIO),
            ['type' => 'ComplexImageTask', 'class' => 'recognition', 'imagesBase64' => ['QUJD'],
                'metadata' => ['Task' => 'bills_audio', 'TaskArgument' => '123', 'PayloadType' => 'Audio']],
        ];
        yield 'image to text' => [
            new Requests\ImageToTextRequest('QUJD', TextModule::UNIVERSAL, 70, true, true, false),
            ['type' => 'ImageToTextTask', 'body' => 'QUJD', 'capMonsterModule' => 'universal', 'recognizingThreshold' => 70,
                'case' => true, 'numeric' => 1, 'math' => false],
        ];
        yield 'funcaptcha' => [
            new Requests\FuncaptchaRequest($u, 'PK', self::proxy(), funcaptchaApiJsSubdomain: 'x.arkoselabs.com', data: '{"blob":"b"}'),
            ['type' => 'FunCaptchaTask', 'websiteURL' => $u, 'websitePublicKey' => 'PK',
                'funcaptchaApiJSSubdomain' => 'x.arkoselabs.com', 'data' => '{"blob":"b"}'] + $p,
        ];
        yield 'geetest v3' => [
            Requests\GeetestRequest::v3($u, 'GT', 'CH', geetestGetLib: '{"static_servers":["x"]}'),
            ['type' => 'GeeTestTask', 'websiteURL' => $u, 'gt' => 'GT', 'version' => 3, 'challenge' => 'CH',
                'geetestGetLib' => '{"static_servers":["x"]}'],
        ];
        yield 'geetest v4' => [
            Requests\GeetestRequest::v4($u, 'CID', ['riskType' => 'slide'], self::proxy(), userAgent: 'UA'),
            ['type' => 'GeeTestTask', 'websiteURL' => $u, 'gt' => 'CID', 'version' => 4,
                'initParameters' => ['riskType' => 'slide'], 'userAgent' => 'UA'] + $p,
        ];
        yield 'geetest v4 without init parameters' => [
            Requests\GeetestRequest::v4($u, 'CID', []),
            ['type' => 'GeeTestTask', 'websiteURL' => $u, 'gt' => 'CID', 'version' => 4],
        ];
        yield 'turnstile standard' => [
            Requests\TurnstileRequest::standard($u, '0xKEY', pageAction: 'login', data: 'cdata'),
            ['type' => 'TurnstileTask', 'websiteURL' => $u, 'websiteKey' => '0xKEY', 'pageAction' => 'login', 'data' => 'cdata'],
        ];
        yield 'turnstile challenge token' => [
            Requests\TurnstileRequest::challengeToken($u, '0xKEY', 'managed', 'UA', 'cdata', 'pdata', apiJsUrl: 'https://x/api.js'),
            ['type' => 'TurnstileTask', 'websiteURL' => $u, 'websiteKey' => '0xKEY', 'cloudflareTaskType' => 'token',
                'pageAction' => 'managed', 'userAgent' => 'UA', 'data' => 'cdata', 'pageData' => 'pdata', 'apiJsUrl' => 'https://x/api.js'],
        ];
        yield 'turnstile cf_clearance' => [
            Requests\TurnstileRequest::challengeCookie($u, 'xxxxxx', 'SFRNTA==', 'UA', self::proxy()),
            ['type' => 'TurnstileTask', 'websiteURL' => $u, 'websiteKey' => 'xxxxxx', 'cloudflareTaskType' => 'cf_clearance',
                'userAgent' => 'UA', 'htmlPageBase64' => 'SFRNTA=='] + $p,
        ];
        yield 'turnstile wait room' => [
            Requests\TurnstileRequest::waitRoom($u, 'xxxxxx', 'SFRNTA==', 'UA', self::proxy()),
            ['type' => 'TurnstileTask', 'websiteURL' => $u, 'websiteKey' => 'xxxxxx', 'cloudflareTaskType' => 'wait_room',
                'userAgent' => 'UA', 'htmlPageBase64' => 'SFRNTA=='] + $p,
        ];
        yield 'amazon visible' => [
            Requests\AmazonWafRequest::visibleCaptcha($u, 'KEY', 'https://x/jsapi.js', self::proxy(), 'UA', true),
            ['type' => 'AmazonTask', 'websiteURL' => $u, 'websiteKey' => 'KEY', 'captchaScript' => 'https://x/jsapi.js',
                'userAgent' => 'UA', 'cookieSolution' => true] + $p,
        ];
        yield 'amazon challenge and captcha' => [
            Requests\AmazonWafRequest::challengeAndCaptcha($u, 'https://x/challenge.js', 'K', 'C', 'IV', 'https://x/captcha.js'),
            ['type' => 'AmazonTask', 'websiteURL' => $u, 'websiteKey' => 'K', 'captchaScript' => 'https://x/captcha.js',
                'challengeScript' => 'https://x/challenge.js', 'context' => 'C', 'iv' => 'IV'],
        ];
        yield 'amazon challenge only sends empty context and iv' => [
            Requests\AmazonWafRequest::challengeOnly($u, 'https://x/challenge.js', cookieSolution: true),
            ['type' => 'AmazonTask', 'websiteURL' => $u, 'challengeScript' => 'https://x/challenge.js', 'context' => '', 'iv' => '',
                'cookieSolution' => true],
        ];
        yield 'binance' => [
            new Requests\BinanceTaskRequest($u, 'login', 'vid', userAgent: 'UA'),
            ['type' => 'BinanceTask', 'websiteURL' => $u, 'websiteKey' => 'login', 'validateId' => 'vid', 'userAgent' => 'UA'],
        ];
        yield 'mtcaptcha' => [
            new Requests\MtCaptchaRequest($u, 'MTPublic-x', pageAction: 'login', isInvisible: true),
            ['type' => 'MTCaptchaTask', 'websiteURL' => $u, 'websiteKey' => 'MTPublic-x', 'pageAction' => 'login', 'isInvisible' => true],
        ];
        yield 'yidun enterprise' => [
            new Requests\YidunRequest($u, 'SK', yidunGetLib: 'https://x/load.js', yidunApiServerSubdomain: 'c.example.com', challenge: 'CH', hcg: 'H', hct: 1700000000000),
            ['type' => 'YidunTask', 'websiteURL' => $u, 'websiteKey' => 'SK', 'yidunGetLib' => 'https://x/load.js',
                'yidunApiServerSubdomain' => 'c.example.com', 'challenge' => 'CH', 'hcg' => 'H', 'hct' => 1700000000000],
        ];
        yield 'prosopo' => [
            new Requests\ProsopoTaskRequest($u, 'SK'),
            ['type' => 'ProsopoTask', 'websiteURL' => $u, 'websiteKey' => 'SK'],
        ];
        yield 'datadome' => [
            new Requests\DataDomeCustomTaskRequest($u, 'https://geo.captcha-delivery.com/captcha/?t=fe', 'datadome=abc', self::proxy(),
                Requests\DataDomeCustomTaskRequest::VERSION_NEW, 'UA'),
            ['type' => 'CustomTask', 'class' => 'DataDome', 'websiteURL' => $u,
                'metadata' => ['captchaUrl' => 'https://geo.captcha-delivery.com/captcha/?t=fe', 'datadomeCookie' => 'datadome=abc', 'datadomeVersion' => 'new'],
                'userAgent' => 'UA'] + $p,
        ];
        yield 'imperva' => [
            new Requests\ImpervaCustomTaskRequest($u, '_Incapsula_Resource?x=1', 'incap_ses=1', self::proxy(), '/endpoint?d=site.com'),
            ['type' => 'CustomTask', 'class' => 'Imperva', 'websiteURL' => $u,
                'metadata' => ['incapsulaScriptUrl' => '_Incapsula_Resource?x=1', 'incapsulaCookies' => 'incap_ses=1', 'reese84UrlEndpoint' => '/endpoint?d=site.com']] + $p,
        ];
        yield 'tspd' => [
            new Requests\TspdCustomTaskRequest($u, 'TS1=2;', 'SFRNTA==', self::proxy()),
            ['type' => 'CustomTask', 'class' => 'tspd', 'websiteURL' => $u, 'metadata' => ['tspdCookie' => 'TS1=2;', 'htmlPageBase64' => 'SFRNTA==']] + $p,
        ];
        yield 'hunt fingerprint' => [
            Requests\HuntCustomTaskRequest::fingerprint($u, 'https://x/api.js', self::proxy()),
            ['type' => 'CustomTask', 'class' => 'HUNT', 'websiteURL' => $u, 'metadata' => ['apiGetLib' => 'https://x/api.js']] + $p,
        ];
        yield 'hunt token' => [
            Requests\HuntCustomTaskRequest::byToken($u, 'https://x/api.js', 'TOKEN', self::proxy(), 'UA'),
            ['type' => 'CustomTask', 'class' => 'HUNT', 'websiteURL' => $u, 'metadata' => ['apiGetLib' => 'https://x/api.js', 'data' => 'TOKEN'],
                'userAgent' => 'UA'] + $p,
        ];
        yield 'hunt widget url' => [
            Requests\HuntCustomTaskRequest::byWidgetUrl($u, 'https://x/api.js', 'https://c/widget?hash=1', self::proxy()),
            ['type' => 'CustomTask', 'class' => 'HUNT', 'websiteURL' => $u, 'metadata' => ['apiGetLib' => 'https://x/api.js', 'widgetUrl' => 'https://c/widget?hash=1']] + $p,
        ];
        yield 'basilisk' => [
            new Requests\BasiliskCustomTaskRequest($u, 'KEY', userAgent: 'UA'),
            ['type' => 'CustomTask', 'class' => 'Basilisk', 'websiteURL' => $u, 'websiteKey' => 'KEY', 'userAgent' => 'UA'],
        ];
        yield 'tendi minimal has no metadata' => [
            new Requests\TenDiCustomTaskRequest($u, '189123456'),
            ['type' => 'CustomTask', 'class' => 'TenDI', 'websiteURL' => $u, 'websiteKey' => '189123456'],
        ];
        yield 'tendi with metadata' => [
            new Requests\TenDiCustomTaskRequest($u, '189123456', captchaUrl: 'https://x/TCaptcha.js', aidEncrypted: 'AID'),
            ['type' => 'CustomTask', 'class' => 'TenDI', 'websiteURL' => $u, 'websiteKey' => '189123456',
                'metadata' => ['captchaUrl' => 'https://x/TCaptcha.js', 'aidEncrypted' => 'AID']],
        ];
        yield 'alibaba' => [
            new Requests\AlibabaCustomTaskRequest($u, 'S', 'P', region: 'sgp', userCertifyId: 'CID', cookieRequired: true),
            ['type' => 'CustomTask', 'class' => 'alibaba', 'websiteURL' => $u,
                'metadata' => ['sceneId' => 'S', 'prefix' => 'P', 'region' => 'sgp', 'UserCertifyId' => 'CID', 'cookieRequired' => true]],
        ];
        yield 'friendly' => [
            new Requests\FriendlyCustomTaskRequest($u, 'FCKEY', 'https://x/site.min.js'),
            ['type' => 'CustomTask', 'class' => 'friendly', 'websiteURL' => $u, 'websiteKey' => 'FCKEY', 'metadata' => ['apiGetLib' => 'https://x/site.min.js']],
        ];
        yield 'altcha with empty website key' => [
            new Requests\AltchaCustomTaskRequest($u, '', 'c', '1', 's', 'g'),
            ['type' => 'CustomTask', 'class' => 'altcha', 'websiteURL' => $u, 'websiteKey' => '',
                'metadata' => ['challenge' => 'c', 'iterations' => '1', 'salt' => 's', 'signature' => 'g']],
        ];
    }

    #[DataProvider('tasks')]
    public function testTaskJson(CaptchaRequest $request, array $expected): void
    {
        $task = $request->toTask();
        ksort($task);
        ksort($expected);
        self::assertSame($expected, $task);
        self::assertGreaterThan(0, $request->timeouts()->timeout);
    }

    /** @return iterable<string, array{\Closure(): CaptchaRequest, string}> */
    public static function invalidRequests(): iterable
    {
        $u = self::URL;

        yield 'empty websiteURL' => [fn () => new Requests\RecaptchaV2Request('', 'KEY'), 'websiteURL'];
        yield 'empty websiteKey' => [fn () => Requests\TurnstileRequest::standard($u, ''), 'websiteKey'];
        yield 'minScore too high' => [fn () => new Requests\RecaptchaV3ProxylessRequest($u, 'KEY', 1.1), 'minScore'];
        yield 'minScore too low (enterprise)' => [fn () => new Requests\RecaptchaV3EnterpriseRequest($u, 'KEY', 0.05), 'minScore'];
        yield 'empty enterprisePayload' => [fn () => new Requests\RecaptchaV2EnterpriseRequest($u, 'KEY', enterprisePayload: []), 'enterprisePayload'];
        yield 'threshold out of range' => [fn () => new Requests\ImageToTextRequest('QUJD', recognizingThreshold: 101), 'recognizingThreshold'];
        yield 'empty image body' => [fn () => new Requests\ImageToTextRequest(''), 'body'];
        yield 'data uri body' => [fn () => new Requests\ImageToTextRequest('data:image/png;base64,QUJD'), 'base64'];
        yield 'geetest v3 empty challenge' => [fn () => Requests\GeetestRequest::v3($u, 'GT', ''), 'challenge'];
        yield 'turnstile token without pageData' => [fn () => Requests\TurnstileRequest::challengeToken($u, 'K', 'managed', 'UA', 'd', ''), 'pageData'];
        yield 'turnstile cookie without html' => [fn () => Requests\TurnstileRequest::challengeCookie($u, 'K', '', 'UA', self::proxy()), 'htmlPageBase64'];
        yield 'amazon visible without script' => [fn () => Requests\AmazonWafRequest::visibleCaptcha($u, 'K', ''), 'captchaScript'];
        yield 'amazon option 2 without iv' => [fn () => Requests\AmazonWafRequest::challengeAndCaptcha($u, 'S', 'K', 'C', ''), 'iv'];
        yield 'recaptcha click without task' => [fn () => new Requests\RecaptchaComplexImageTaskRequest(RecaptchaGrid::Grid3x3, imageUrls: ['u']), 'task'];
        yield 'recaptcha click without images' => [fn () => new Requests\RecaptchaComplexImageTaskRequest(RecaptchaGrid::Grid3x3, 'T'), 'imageUrls'];
        yield 'recaptcha click two images' => [fn () => new Requests\RecaptchaComplexImageTaskRequest(RecaptchaGrid::Grid3x3, 'T', imageUrls: ['a', 'b']), 'at most 1'];
        yield 'recognition without images' => [fn () => new Requests\RecognitionComplexImageTaskRequest([], 'shein'), 'imagesBase64'];
        yield 'recognition without task' => [fn () => new Requests\RecognitionComplexImageTaskRequest(['QUJD'], ''), 'Task'];
        yield 'datadome empty cookie' => [fn () => new Requests\DataDomeCustomTaskRequest($u, 'U', '', self::proxy()), 'datadomeCookie'];
        yield 'hunt empty token' => [fn () => Requests\HuntCustomTaskRequest::byToken($u, 'L', '', self::proxy()), 'metadata.data'];
        yield 'tspd bad proxy login' => [
            fn () => new Requests\TspdCustomTaskRequest($u, 'C', 'H', new ProxyInfo(ProxyType::Http, 'h', 1, 'a|b', 'p')),
            'proxy login',
        ];
        yield 'altcha empty salt' => [fn () => new Requests\AltchaCustomTaskRequest($u, '', 'c', '1', '', 'g'), 'metadata.salt'];
        yield 'alibaba empty prefix' => [fn () => new Requests\AlibabaCustomTaskRequest($u, 'S', ''), 'metadata.prefix'];
        yield 'binance empty validateId' => [fn () => new Requests\BinanceTaskRequest($u, 'login', ''), 'validateId'];
    }

    /** @param \Closure(): CaptchaRequest $make */
    #[DataProvider('invalidRequests')]
    public function testValidation(\Closure $make, string $messagePart): void
    {
        $this->expectException(ValidationException::class);
        $this->expectExceptionMessage($messagePart);
        $make()->toTask();
    }

    public function testProxyRejectsBadPort(): void
    {
        $this->expectException(ValidationException::class);
        new ProxyInfo(ProxyType::Socks5, 'h', 70000);
    }

    public function testImageToTextFromFile(): void
    {
        $file = tempnam(sys_get_temp_dir(), 'img');
        file_put_contents($file, 'ABC');
        try {
            $task = Requests\ImageToTextRequest::fromFile($file, TextModule::YANDEX)->toTask();
        } finally {
            unlink($file);
        }
        self::assertSame(['type' => 'ImageToTextTask', 'body' => 'QUJD', 'capMonsterModule' => 'yandex'], $task);
    }

    public function testSecretsAreMaskedInDumps(): void
    {
        $dump = print_r(self::proxy(), true) . print_r(new \CapMonsterCloud\ClientOptions('SECRETKEY'), true);
        self::assertStringNotContainsString('SECRETKEY', $dump);
        self::assertStringNotContainsString("[password] => p\n", $dump);
    }
}
