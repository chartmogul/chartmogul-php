<?php
namespace ChartMogul\Tests;

use ChartMogul\Http\Client;
use ChartMogul\Account;
use ChartMogul\Exceptions\ChartMogulException;
use GuzzleHttp\Psr7;
use GuzzleHttp\Psr7\Response;
use ChartMogul\Exceptions\NotFoundException;

class AccountTest extends TestCase
{
    const RETRIEVE_ACCOUNT = '{
    "name": "Example Test Company",
    "currency": "EUR",
    "time_zone": "Europe/Berlin",
    "week_start_on": "sunday"
  }';

    public function testRetrieveAccount()
    {
        $stream = Psr7\stream_for(AccountTest::RETRIEVE_ACCOUNT);
        list($cmClient, $mockClient) = $this->getMockClient(0, [200], $stream);

        $result = Account::retrieve($cmClient);
        $request = $mockClient->getRequests()[0];

        $this->assertEquals("GET", $request->getMethod());
        $uri = $request->getUri();
        $this->assertEquals("", $uri->getQuery());
        $this->assertEquals("/v1/account", $uri->getPath());

        $this->assertTrue($result instanceof Account);
        $this->assertEquals($result->name, "Example Test Company");
        $this->assertEquals($result->currency, "EUR");
        $this->assertEquals($result->time_zone, "Europe/Berlin");
        $this->assertEquals($result->week_start_on, "sunday");
    }
}
