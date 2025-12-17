<?php
namespace ChartMogul\Tests;

use ChartMogul\Http\Client;
use ChartMogul\Configuration;
use ChartMogul\Exceptions\NetworkException;
use GuzzleHttp\Psr7\Response;

class ClientNullResponseTest extends TestCase
{
    public function testSendWithValidResponse()
    {
        $stream = \GuzzleHttp\Psr7\stream_for('{"test": "data"}');
        list($cmClient, $mockClient) = $this->getMockClient(0, [200], $stream);

        $result = $cmClient->send('/test', 'GET');

        $this->assertEquals(['test' => 'data'], $result);
    }
}
