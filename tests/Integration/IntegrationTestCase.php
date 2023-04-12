<?php
namespace ChartMogul\IntegrationTests;

use ChartMogul\Configuration;
use ChartMogul\Http\Client;
use VCR\VCR;

class IntegrationTestCase extends \PHPUnit\Framework\TestCase
{
    public $client = null;

    public function setUp(): void
    {
        VCR::configure()
            ->enableLibraryHooks(['curl'])
            ->setCassettePath('tests/Fixtures')
            ->enableRequestMatchers(['method', 'url', 'body', 'query_string']);
        VCR::turnOn();
    }

    public function tearDown(): void
    {
        VCR::eject();
        VCR::turnOff();
    }

    public function setApiKey($apiKey)
    {
        $this->client = new Client(new Configuration($apiKey));
        return $this;
    }
}
