<?php
namespace ChartMogul\Tests;

use ChartMogul\Http\Client;
use ChartMogul\Metrics;
use ChartMogul\Metrics\Activity;
use ChartMogul\Resource\CollectionWithCursor;
use ChartMogul\Exceptions\ChartMogulException;
use GuzzleHttp\Psr7;
use GuzzleHttp\Psr7\Response;

class ActivityTest extends TestCase
{

    const ALL_JSON = '{
        "entries":[
            {
              "description": "purchased the plan_11 plan",
              "activity-mrr-movement": 6000,
              "activity-mrr": 6000,
              "activity-arr": 72000,
              "date": "2020-05-06T01:00:00",
              "type": "new_biz",
              "currency": "USD",
              "subscription-external-id": "sub_2",
              "plan-external-id": "11",
              "customer-name": "customer_2",
              "customer-uuid": "8bc55ab6-c3b5-11eb-ac45-2f9a49d75af7",
              "customer-external-id": "customer_2",
              "billing-connector-uuid": "99076cb8-97a1-11eb-8798-a73b507e7929",
              "uuid": "f1a49735-21c7-4e3f-9ddc-67927aaadcf4"
            }
        ],
        "has_more":false,
        "cursor": "cursor=="
    }';


    public function testAll()
    {
        $stream = Psr7\stream_for(ActivityTest::ALL_JSON);
        list($cmClient, $mockClient) = $this->getMockClient(0, [200], $stream);

        $result = Activity::all(['type' => 'new_biz'], $cmClient);
        $request = $mockClient->getRequests()[0];

        $this->assertEquals("GET", $request->getMethod());
        $uri = $request->getUri();
        $this->assertEquals("/v1/activities", $uri->getPath());

        $this->assertTrue($result->entries[0] instanceof Activity);
        $this->assertEquals($result->entries[0]->type, "new_biz");
        $this->assertEquals($result->has_more, false);
        $this->assertEquals($result->cursor, "cursor==");
    }
}
