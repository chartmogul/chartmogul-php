<?php
namespace ChartMogul\Tests;

use ChartMogul\Http\Client;
use GuzzleHttp\Psr7\Response;
use GuzzleHttp\Psr7;
use Http\Discovery\HttpClientDiscovery;
use Http\Discovery\Strategy\MockClientStrategy;

class TestCase extends \PHPUnit\Framework\TestCase
{
    public function setUp(): void
    {
        HttpClientDiscovery::prependStrategy(MockClientStrategy::class);
    }

    protected function getMockClient($retries, $statuses, $stream = null, $exceptions = [])
    {
        $mock = $this->getMockBuilder(Client::class)
            ->onlyMethods(['getBasicAuthHeader', 'getUserAgent'])
            ->getMock();

        $mock->expects($this->once())
            ->method('getBasicAuthHeader')
            ->willReturn('auth');

        $mock->expects($this->once())
            ->method('getUserAgent')
            ->willReturn('agent');


        $mock->setConfiguration(\ChartMogul\Configuration::getDefaultConfiguration()->setRetries($retries));
        $mockClient = $mock->getHttpClient();
        foreach($statuses as $status) {
            $response = new Response($status, ['Content-Type' => 'application/json'], $stream);
            $mockClient->addResponse($response);
        }
        foreach($exceptions as $exception) {
            $mockClient->addException($exception);
        }
        $mock->setHttpClient($mockClient);
        return [$mock, $mockClient];
    }

    protected function assertDeprecation(string $expectedMessage, callable $callback)
    {
        $captured = [];
        set_error_handler(function ($errno, $errstr) use (&$captured) {
            $captured[] = [$errno, $errstr];
            return true;
        }, E_USER_DEPRECATED);

        try {
            $callback();
        } finally {
            restore_error_handler();
        }

        $this->assertContains([E_USER_DEPRECATED, $expectedMessage], $captured);
    }

    protected function getMockClientException($retries, $statuses, $stream = null, $exceptions = [])
    {
        foreach($exceptions as $exception) {
            $this->expectException($exception);
        }

        $mock = $this->getMockBuilder(Client::class)
            ->onlyMethods(['getBasicAuthHeader', 'getUserAgent'])
            ->getMock();
        $mock->setConfiguration(\ChartMogul\Configuration::getDefaultConfiguration()->setRetries($retries));
        $mockClient = $mock->getHttpClient();
        $mock->setHttpClient($mockClient);
        return [$mock, $mockClient];
    }
}
