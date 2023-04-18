<?php
namespace ChartMogul\Tests;

use ChartMogul\Http\Client;
use ChartMogul\Exceptions\ChartMogulException;
use GuzzleHttp\Psr7\Response;
use GuzzleHttp\Psr7;
use Http\Client\Exception\NetworkException;
use Http\Client\HttpClient;
use Http\Discovery\MessageFactoryDiscovery;

class ClientTest extends TestCase
{
    public function setUp(): void
    {
        parent::setUp();
        $this->emptyStream = Psr7\stream_for('{}');
    }

    public function testConstructor()
    {
        $configStub = $this->getMockBuilder(\ChartMogul\Configuration::class)
            ->disableOriginalConstructor()
            ->getMock();

        $client = new Client($configStub);
        $this->assertEquals($configStub, $client->getConfiguration());
        $this->assertInstanceOf(HttpClient::class, $client->getHttpClient());
    }

    public function testGetUserAgent()
    {
        $mock = $this->getMockBuilder(Client::class)
            ->disableOriginalConstructor()
            ->onlyMethods([])
            ->getMock();

        $this->assertEquals("chartmogul-php/5.1.0/PHP-".PHP_MAJOR_VERSION.".".PHP_MINOR_VERSION, $mock->getUserAgent());
    }

    public function testGetBasicAuthHeader()
    {
        $configStub = $this->getMockBuilder(\ChartMogul\Configuration::class)
            ->disableOriginalConstructor()
            ->getMock();

        $configStub->expects($this->once())
            ->method('getApiKey')
            ->willReturn('token');

        $config = new Client($configStub);
        $data = 'Basic '. base64_encode('token:');
        $this->assertEquals($data, $config->getBasicAuthHeader());
    }


    public function testHandleResponseOK()
    {
        $mock = $this->getMockBuilder(Client::class)
            ->disableOriginalConstructor()
            ->onlyMethods([])
            ->getMock();

        $res = MessageFactoryDiscovery::find()->createResponse(
            200,
            null,
            [],
            '{"result": "json"}'
        );
        $data = $mock->handleResponse($res);
        $this->assertEquals($data, ['result' => 'json']);
    }

    public static function provider()
    {
        return array(
            array(200, \ChartMogul\Exceptions\ChartMogulException::class),
            array(400, \ChartMogul\Exceptions\SchemaInvalidException::class),
            array(401, \ChartMogul\Exceptions\ForbiddenException::class),
            array(403, \ChartMogul\Exceptions\ForbiddenException::class),
            array(404, \ChartMogul\Exceptions\NotFoundException::class),
            array(422, \ChartMogul\Exceptions\SchemaInvalidException::class),
            array(500, \ChartMogul\Exceptions\ChartMogulException::class),
        );
    }
    /**
     * @dataProvider provider
     */
    public function testHandleResponseExceptions($status, $exception)
    {
        $this->expectException($exception);
        $mock = $this->getMockBuilder(Client::class)
            ->disableOriginalConstructor()
            ->onlyMethods([])
            ->getMock();

        $res = MessageFactoryDiscovery::find()->createResponse(
            $status,
            null,
            [],
            'plain text'
        );
        $mock->handleResponse($res);
    }

    public static function providerSend()
    {
        return [
            [
                //$path, $method, $data, $query, $rBody
                'foo',   'GET',   [],    '/foo',  ''
            ],
            [
                'foo',   'GET',   ['a'=>'b'], '/foo?a=b', ''
            ],
            [
                //$path, $method, $data, $query
                'foo',   'POST',   ['a' => 'b'], '/foo', '{"a":"b"}'
            ]
        ];
    }
    /**
     * @dataProvider providerSend
     */
    public function testSend($path, $method, $data, $target, $rBody)
    {
        list($mock, $mockClient) = $this->getMockClient(20, [200], $this->emptyStream);

        $returnedResponse = $mock->send($path, $method, $data);
        $request= $mockClient->getRequests()[0];
        $this->assertSame($returnedResponse, []);
        $this->assertEquals($request->getHeader('user-agent'), ['agent']);
        $this->assertEquals($request->getHeader('Authorization'), ['auth']);
        $this->assertEquals($request->getHeader('content-type'), ['application/json']);
        $this->assertEquals($request->getMethod(), $method);
        $this->assertEquals($request->getRequestTarget(), $target);
        $request->getBody()->rewind();
        $this->assertEquals($request->getBody()->getContents(), $rBody);
    }

    public function testNoRetry()
    {
        $this->expectException(ChartMogulException::class);
        list($mock, $mockClient) = $this->getMockClient(0, [500], $this->emptyStream);

        $mock->send('', 'GET', []);
        $this->assertEquals(count($mockClient->getRequests()), 1);
    }

    public function testRetryHTTP()
    {
        list($mock, $mockClient) = $this->getMockClient(10, [500, 429, 200], $this->emptyStream);

        $mock->send('', 'GET', []);
        $this->assertEquals(count($mockClient->getRequests()), 3);
    }
    public function testRetryNetworkError()
    {
        list($mock, $mockClient) = $this->getMockClient(10, [200], $this->emptyStream, [
            new \Http\Client\Exception\NetworkException("some error", $this->createMock('Psr\Http\Message\RequestInterface')),
        ]);

        $mock->send('', 'GET', []);
        $this->assertEquals(count($mockClient->getRequests()), 2);
    }

    public function testRetryMaxAttemptReached()
    {
        $this->expectException(NetworkException::class);
        list($mock, $mockClient) = $this->getMockClient(1, [200], $this->emptyStream, [
            new \Http\Client\Exception\NetworkException("some error", $this->createMock('Psr\Http\Message\RequestInterface')),
            new \Http\Client\Exception\NetworkException("some error", $this->createMock('Psr\Http\Message\RequestInterface')),
        ]);


        $returnedResponse = $mock->send('', 'GET', []);
        $this->assertEquals(count($mockClient->getRequests()), 1);
    }
}
