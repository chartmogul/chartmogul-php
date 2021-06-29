<?php
namespace ChartMogul\Tests;

use ChartMogul\Http\Client;
use ChartMogul\Metrics\Customers;
use ChartMogul\Metrics\Customers\Activity;
use ChartMogul\Exceptions\ChartMogulException;
use GuzzleHttp\Psr7;
use GuzzleHttp\Psr7\Response;

class CustomerActivityTest extends TestCase
{

    const ALL_JSON = '{
        "entries":[
            {
              "activity-arr": 24000,
              "activity-mrr": 2000,
              "activity-mrr-movement": 2000,
              "currency": "USD",
              "currency-sign": "$",
              "date": "2015-06-09T13:16:00-04:00",
              "description": "purchased the Silver Monthly plan (1)",
              "id": 48730,
              "type": "new_biz",
              "subscription-external-id": "1"
            }
        ],
        "has_more":false,
        "per_page":200,
        "page":1
    }';


    public function testAll()
    {
        $stream = Psr7\stream_for(CustomerActivityTest::ALL_JSON);
        list($cmClient, $mockClient) = $this->getMockClient(0, [200], $stream);

        $result = Activity::all(['customer_uuid'=>'cus_0fe70ccc-8e23-11eb-a532-031f31dc363e'],$cmClient);
        $request = $mockClient->getRequests()[0];

        $this->assertEquals("GET", $request->getMethod());
        $uri = $request->getUri();
        $this->assertEquals("/v1/customers/cus_0fe70ccc-8e23-11eb-a532-031f31dc363e/activities", $uri->getPath());

        $this->assertTrue($result->entries[0] instanceof Activity);
        $this->assertEquals($result->entries[0]->type, "new_biz");

    }
}
