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
    "id": "acct_00000000-0000-0000-0000-000000000000",
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
        $this->assertEquals("acct_00000000-0000-0000-0000-000000000000", $result->id);
        $this->assertEquals("Example Test Company", $result->name);
        $this->assertEquals("EUR", $result->currency);
        $this->assertEquals("Europe/Berlin", $result->time_zone);
        $this->assertEquals("sunday", $result->week_start_on);
    }

    public function testRetrieveAccountWithIncludeParams()
    {
        $stream = Psr7\stream_for(AccountTest::RETRIEVE_ACCOUNT);
        list($cmClient, $mockClient) = $this->getMockClient(0, [200], $stream);

        $result = Account::retrieve($cmClient, [
            'churn_recognition' => true,
            'churn_when_zero_mrr' => true,
        ]);
        $request = $mockClient->getRequests()[0];

        $this->assertEquals("GET", $request->getMethod());
        $uri = $request->getUri();
        parse_str($uri->getQuery(), $queryParams);
        $this->assertEquals('1', $queryParams['churn_recognition']);
        $this->assertEquals('1', $queryParams['churn_when_zero_mrr']);
        $this->assertEquals("/v1/account", $uri->getPath());

        $this->assertTrue($result instanceof Account);
        $this->assertEquals("Example Test Company", $result->name);
    }
}
