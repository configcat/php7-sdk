<?php

namespace ConfigCat\Tests;

use ConfigCat\ClientOptions;
use ConfigCat\ConfigFetcher;
use ConfigCat\Tests\Helpers\Utils;
use GuzzleHttp\Exception\ConnectException;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Psr7\Request;
use GuzzleHttp\Psr7\Response;
use InvalidArgumentException;
use PHPUnit\Framework\TestCase;

class ConfigFetcherTest extends TestCase
{
    private $mockSdkKey = 'testSdkKey';
    private $mockEtag = 'testEtag';
    private $mockBody = '{"key": "value"}';

    public function testFetchOk()
    {
        $mockHandler = new MockHandler([
            new Response(200, [ConfigFetcher::ETAG_HEADER => $this->mockEtag], $this->mockBody), ]);
        $fetcher = new ConfigFetcher($this->mockSdkKey, Utils::getTestLogger(), [
            ClientOptions::CUSTOM_HANDLER => HandlerStack::create($mockHandler), ]);

        $response = $fetcher->fetch('old_etag');

        $this->assertTrue($response->isFetched());
        $this->assertEquals($this->mockEtag, $response->getConfigEntry()->getEtag());
        $this->assertEquals('value', $response->getConfigEntry()->getConfig()['key']);
        $this->assertNotEmpty($mockHandler->getLastRequest()->getHeader('X-ConfigCat-UserAgent'));
    }

    public function testFetchNotModified()
    {
        $fetcher = new ConfigFetcher($this->mockSdkKey, Utils::getTestLogger(), [
            ClientOptions::CUSTOM_HANDLER => HandlerStack::create(new MockHandler([
                new Response(304, [ConfigFetcher::ETAG_HEADER => $this->mockEtag]), ])), ]);

        $response = $fetcher->fetch('');

        $this->assertTrue($response->isNotModified());
        $this->assertEmpty($response->getConfigEntry()->getEtag());
        $this->assertEmpty($response->getConfigEntry()->getConfig());
    }

    public function testFetchFailed()
    {
        $fetcher = new ConfigFetcher($this->mockSdkKey, Utils::getTestLogger(), [
            ClientOptions::CUSTOM_HANDLER => HandlerStack::create(new MockHandler([new Response(400)])), ]);

        $response = $fetcher->fetch('');

        $this->assertTrue($response->isFailed());
        $this->assertEmpty($response->getConfigEntry()->getEtag());
        $this->assertEmpty($response->getConfigEntry()->getConfig());
    }

    public function testFetchInvalidJson()
    {
        $mockHandler = new MockHandler([new Response(200, [], '{"key": value}')]);
        $fetcher = new ConfigFetcher($this->mockSdkKey, Utils::getTestLogger(), [
            ClientOptions::CUSTOM_HANDLER => HandlerStack::create($mockHandler), ]);

        $response = $fetcher->fetch('');

        $this->assertTrue($response->isFailed());
        $this->assertEmpty($response->getConfigEntry()->getEtag());
        $this->assertEmpty($response->getConfigEntry()->getConfig());
        $this->assertNotEmpty($mockHandler->getLastRequest()->getHeader('X-ConfigCat-UserAgent'));
    }

    public function testConstructEmptySdkKey()
    {
        $this->expectException(InvalidArgumentException::class);
        new ConfigFetcher('', Utils::getNullLogger());
    }

    public function testTimeoutException()
    {
        $fetcher = new ConfigFetcher('api', Utils::getTestLogger(), [ClientOptions::CUSTOM_HANDLER => HandlerStack::create(new MockHandler([
            new ConnectException('timeout', new Request('GET', 'test')),
        ]))]);
        $response = $fetcher->fetch('');
        $this->assertTrue($response->isFailed());
    }

    public function testIntegration()
    {
        $fetcher = new ConfigFetcher('PKDVCLf-Hq-h-kCzMp-L7Q/PaDVCFk9EpmD6sLpGLltTA', Utils::getTestLogger());
        $response = $fetcher->fetch('');

        $this->assertTrue($response->isFetched());

        $notModifiedResponse = $fetcher->fetch($response->getConfigEntry()->getEtag());

        $this->assertTrue($notModifiedResponse->isNotModified());
    }
}
