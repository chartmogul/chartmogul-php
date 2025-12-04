<?php
namespace ChartMogul\Tests;

use ChartMogul\Http\Client;
use ChartMogul\Configuration;
use ChartMogul\Exceptions\NetworkException;
use GuzzleHttp\Psr7\Response;

class ClientNullResponseTest extends TestCase
{
    public function testHandleResponseWithNull()
    {
        $this->expectException(NetworkException::class);
        $this->expectExceptionMessage('No response received from server');

        $config = new Configuration('test_key');
        $client = new Client($config);

        // Test handleResponse directly with null
        $client->handleResponse(null);
    }

    public function testSendWithValidResponse()
    {
        $stream = \GuzzleHttp\Psr7\stream_for('{"test": "data"}');
        list($cmClient, $mockClient) = $this->getMockClient(0, [200], $stream);

        $result = $cmClient->send('/test', 'GET');

        $this->assertEquals(['test' => 'data'], $result);
    }
}
