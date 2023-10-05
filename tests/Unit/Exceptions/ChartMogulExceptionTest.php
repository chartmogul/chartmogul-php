<?php
namespace ChartMogul\Tests\Exceptions;

use ChartMogul\Exceptions\ChartMogulException;
use Nyholm\Psr7\Factory\Psr17Factory;
use Nyholm\Psr7\Response;

class ChartMogulExceptionTest extends \PHPUnit\Framework\TestCase
{

    public function testConstruct()
    {
        $psr17Factory = new Psr17Factory();

        // Test non-JSON response
        $mock = new Response(200, [], 'plain text');
        $e = new ChartMogulException('message', $mock);
        $this->assertEquals($e->getStatusCode(), 200);
        $this->assertEquals($e->getResponse(), 'plain text');

        // Test JSON response
        $mock = new Response(200, [], '{"result": "json"}');
        $e = new ChartMogulException('message', $mock);
        $this->assertEquals($e->getStatusCode(), 200);
        $this->assertEquals(
            $e->getResponse(), [
            "result" => "json"
            ]
        );
    }
}
