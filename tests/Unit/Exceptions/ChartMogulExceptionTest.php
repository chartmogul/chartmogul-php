<?php
namespace ChartMogul\Tests\Exceptions;

use ChartMogul\Exceptions\ChartMogulException;
use Http\Discovery\MessageFactoryDiscovery;

class ChartMogulExceptionTest extends \PHPUnit\Framework\TestCase
{

    public function testConstruct()
    {

        // Test non-JSON response
        $mock = MessageFactoryDiscovery::find()->createResponse(
            200,
            null,
            [],
            'plain text'
        );
        $e = new ChartMogulException('message', $mock);
        $this->assertEquals($e->getStatusCode(), 200);
        $this->assertEquals($e->getResponse(), 'plain text');

        // Test JSON response
        $mock = MessageFactoryDiscovery::find()->createResponse(
            200,
            null,
            [],
            '{"result": "json"}'
        );
        $e = new ChartMogulException('message', $mock);
        $this->assertEquals($e->getStatusCode(), 200);
        $this->assertEquals($e->getResponse(), [
            "result" => "json"
        ]);
    }
}
