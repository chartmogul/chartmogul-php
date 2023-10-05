<?php
namespace ChartMogul\Tests;

use ChartMogul\Http\Client;
use ChartMogul\Metrics\Customers;
use ChartMogul\Metrics\Customers\Subscription;
use ChartMogul\Exceptions\ChartMogulException;
use GuzzleHttp\Psr7;
use GuzzleHttp\Psr7\Response;

class CustomerSubscriptionTest extends TestCase
{

    const ALL_JSON = '{
        "entries":[
            {
                "id": 9306830,
                "external_id": "sub_0001",
                "plan": "PRO Plan (10,000 active cust.) monthly",
                "quantity": 1,
                "mrr": 70800,
                "arr": 849600,
                "status": "active",
                "billing-cycle": "month",
                "billing-cycle-count": 1,
                "start-date": "2015-12-20T08:26:49-05:00",
                "end-date": "2016-03-20T09:26:49-05:00",
                "currency": "USD",
                "currency-sign": "$"
            }
        ],
        "has_more":false,
        "per_page":200,
        "page":1
    }';

    const ALL_NEW_PAGINATION_JSON = '{
        "entries":[
            {
                "id": 9306830,
                "external_id": "sub_0001",
                "plan": "PRO Plan (10,000 active cust.) monthly",
                "quantity": 1,
                "mrr": 70800,
                "arr": 849600,
                "status": "active",
                "billing-cycle": "month",
                "billing-cycle-count": 1,
                "start-date": "2015-12-20T08:26:49-05:00",
                "end-date": "2016-03-20T09:26:49-05:00",
                "currency": "USD",
                "currency-sign": "$"
            }
        ],
        "has_more":false,
        "cursor": "cursor=="
    }';


    public function testAll()
    {
        $stream = Psr7\stream_for(CustomerSubscriptionTest::ALL_JSON);
        list($cmClient, $mockClient) = $this->getMockClient(0, [200], $stream);

        $result = Subscription::all(['customer_uuid'=>'cus_0fe70ccc-8e23-11eb-a532-031f31dc363e'], $cmClient);
        $request = $mockClient->getRequests()[0];

        $this->assertEquals("GET", $request->getMethod());
        $uri = $request->getUri();
        $this->assertEquals("/v1/customers/cus_0fe70ccc-8e23-11eb-a532-031f31dc363e/subscriptions", $uri->getPath());

        $this->assertTrue($result->entries[0] instanceof Subscription);
        $this->assertEquals($result->entries[0]->external_id, "sub_0001");
    }

    public function testAllNewPagination()
    {
        $stream = Psr7\stream_for(CustomerSubscriptionTest::ALL_NEW_PAGINATION_JSON);
        list($cmClient, $mockClient) = $this->getMockClient(0, [200], $stream);

        $result = Subscription::all(['customer_uuid'=>'cus_0fe70ccc-8e23-11eb-a532-031f31dc363e'], $cmClient);
        $request = $mockClient->getRequests()[0];

        $this->assertEquals("GET", $request->getMethod());
        $uri = $request->getUri();
        $this->assertEquals("/v1/customers/cus_0fe70ccc-8e23-11eb-a532-031f31dc363e/subscriptions", $uri->getPath());

        $this->assertEquals($result->cursor, "cursor==");
        $this->assertFalse($result->has_more);
    }
}
