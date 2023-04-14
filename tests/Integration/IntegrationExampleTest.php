<?php
namespace ChartMogul\IntegrationTests;

use ChartMogul;
use VCR\VCR;

class IntegrationExampleTest extends IntegrationTestCase
{
    public function testIntegrationExample()
    {
        VCR::insertCassette('IntegrationExampleTest.yaml');

        /*
         * When writing the test, set this to your own API key.
         * Once all fixtures are recorded, remove the line from your test
         * and remove all references to your own API key in the fixtures.
         */
        // $this->setApiKey('<YOUR_API_KEY>');

        $response = ChartMogul\Ping::ping($this->client);
        $this->assertEquals("pong!", $response->data);
    }
}
