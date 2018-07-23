<?php

use ChartMogul\Http\Client;
use ChartMogul\Exceptions\ChartMogulException;

class ClientTest extends \PHPUnit\Framework\TestCase
{

    public function testConstructor()
    {
        $configStub = $this->getMockBuilder(\ChartMogul\Configuration::class)
            ->disableOriginalConstructor()
            ->getMock();

        $clientStub = $this->getMockBuilder(\Http\Client\HttpClient::class)
            ->disableOriginalConstructor()
            ->getMock();


        $client = new Client($configStub, $clientStub);
        $this->assertEquals($configStub, $client->getConfiguration());
        $this->assertEquals($clientStub, $client->getHttpClient());
    }

    public function testGetUserAgent()
    {
        $mock = $this->getMockBuilder(Client::class)
            ->disableOriginalConstructor()
            ->setMethods(null)
            ->getMock();

        $this->assertEquals("chartmogul-php/1.0.0/PHP-".PHP_MAJOR_VERSION.".".PHP_MINOR_VERSION, $mock->getUserAgent());
    }

    public function testGetBasicAuthHeader()
    {
        $configStub = $this->getMockBuilder(\ChartMogul\Configuration::class)
            ->disableOriginalConstructor()
            ->getMock();

        $configStub->expects($this->once())
            ->method('getAccountToken')
            ->willReturn('token');

        $configStub->expects($this->once())
            ->method('getSecretKey')
            ->willReturn('secret');

        $config = new Client($configStub);
        $data = 'Basic '. base64_encode('token:secret');
        $this->assertEquals($data, $config->getBasicAuthHeader());
    }


    public function testHandleResponseOK()
    {
        $mock = $this->getMockBuilder(Client::class)
            ->disableOriginalConstructor()
            ->setMethods(null)
            ->getMock();

        $res = Http\Discovery\MessageFactoryDiscovery::find()->createResponse(
            200,
            null,
            [],
            '{"result": "json"}'
        );
        $data = $mock->handleResponse($res);
        $this->assertEquals($data, ['result' => 'json']);
    }

    public function provider()
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
        $mock = $this->getMockBuilder(Client::class)
            ->disableOriginalConstructor()
            ->setMethods(null)
            ->getMock();

        $this->setExpectedException($exception);

        $res = Http\Discovery\MessageFactoryDiscovery::find()->createResponse(
            $status,
            null,
            [],
            'plain text'
        );
        $mock->handleResponse($res);
    }


    public function providerSend()
    {
        return [
            [
                //$path, $method, $data, $query, $rBody
                'foo',   'GET',   [],    'foo',  ''
            ],
            [
                'foo',   'GET',   ['a'=>'b'], 'foo?a=b', ''
            ],
            [
                //$path, $method, $data, $query
                'foo',   'POST',   ['a' => 'b'], 'foo', '{"a":"b"}'
            ]
        ];
    }
    /**
     * @dataProvider providerSend
     */
    public function testSend($path, $method, $data, $target, $rBody)
    {
        $mock = $this->getMockBuilder(Client::class)
            ->disableOriginalConstructor()
            ->setMethods(['getBasicAuthHeader', 'getUserAgent', 'handleResponse'])
            ->getMock();

        $mock->expects($this->once())
            ->method('getBasicAuthHeader')
            ->willReturn('auth');

        $mock->expects($this->once())
            ->method('getUserAgent')
            ->willReturn('agent');

        $response = $this->getMock('Psr\Http\Message\ResponseInterface');

        $mock->expects($this->once())
            ->method('handleResponse')
            ->willReturn($response);

        $mockClient = new \Http\Mock\Client();
        $mock->setHttpClient($mockClient);
        $mockClient->addResponse($response);


        $returnedResponse = $mock->send($path, $method, $data);
        $request= $mockClient->getRequests()[0];
        $this->assertSame($response, $returnedResponse);
        $this->assertEquals($request->getHeader('user-agent'), ['agent']);
        $this->assertEquals($request->getHeader('Authorization'), ['auth']);
        $this->assertEquals($request->getHeader('content-type'), ['application/json']);
        $this->assertEquals($request->getMethod(), $method);
        $this->assertEquals($request->getRequestTarget(), $target);
        $request->getBody()->rewind();
        $this->assertEquals($request->getBody()->getContents(), $rBody);
    }
}
