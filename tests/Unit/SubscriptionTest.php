<?php

use ChartMogul\Http\Client;
use ChartMogul\Subscription;
use ChartMogul\Exceptions\ChartMogulException;
use GuzzleHttp\Psr7;
use GuzzleHttp\Psr7\Response;

class SubscriptionTest extends PHPUnit_Framework_TestCase
{

  const ALL_SUBS_JSON = '{
    "customer_uuid": "cus_f466e33d-ff2b-4a11-8f85-417eb02157a7",
    "subscriptions": [
        {
            "uuid": "sub_dd169c42-e127-4637-8b8f-a239b248e3cd",
            "external_id": "abc",
            "cancellation_dates": [],
            "plan_uuid": "pl_d6fe6904-8319-11e7-82b4-ffedd86c182a",
            "data_source_uuid": "ds_637442a6-8319-11e7-a280-1f28ec01465c"
        }
    ],
    "current_page": 2,
    "total_pages": 3
}';

const CANCEL_SUB_JSON = '{
    "uuid": "sub_65bc29a4-dbce-42a0-8435-d54b8701e762",
    "external_id": "SB00004G8AMAWS",
    "cancellation_dates": [
        "2018-08-08T00:00:00.000Z"
    ],
    "customer_uuid": "cus_69030f0a-9c36-11e7-997f-979f1762dcb8",
    "plan_uuid": "pl_6a21613e-9c36-11e7-b772-cf778619abb9",
    "data_source_uuid": "ds_51f40656-9b74-11e7-af6f-efb2fa0552a1"
}';
    public function testAllSubscriptions()
    {
        $stream = Psr7\stream_for(SubscriptionTest::ALL_SUBS_JSON);
        $response = new Response(200, ['Content-Type' => 'application/json'], $stream);
        $mockClient = new \Http\Mock\Client();
        $mockClient->addResponse($response);

        $cmClient = new Client(null, $mockClient);
        $query = ['customer_uuid' => 'cus_f466e33d-ff2b-4a11-8f85-417eb02157a7'];
        $result = Subscription::all($query, $cmClient);
        $request = $mockClient->getRequests()[0];

        $this->assertEquals("GET", $request->getMethod());
        $uri = $request->getUri();
        $this->assertEquals("", $uri->getQuery());
        $this->assertEquals("/v1/import/customers/cus_f466e33d-ff2b-4a11-8f85-417eb02157a7/subscriptions", $uri->getPath());

        $this->assertEquals(1, sizeof($result));
        $this->assertTrue($result[0] instanceof Subscription);
        $this->assertEquals("cus_f466e33d-ff2b-4a11-8f85-417eb02157a7", $result->customer_uuid);
        $this->assertEquals("sub_dd169c42-e127-4637-8b8f-a239b248e3cd", $result[0]->uuid);
        $this->assertEquals(2, $result->current_page);
        $this->assertEquals(3, $result->total_pages);
    }

    public function testCancelSubscription()
    {
        $stream = Psr7\stream_for(SubscriptionTest::CANCEL_SUB_JSON);
        $response = new Response(202, ['Content-Type' => 'application/json'], $stream);
        $mockClient = new \Http\Mock\Client();
        $mockClient->addResponse($response);

        $cmClient = new Client(null, $mockClient);
        $subscription = new ChartMogul\Subscription([
          "uuid" => "sub_some_id"
        ], $cmClient);
        $cancel_date = "2018-08-08T00:00:00.000Z";
        $result = $subscription->cancel($cancel_date);
        $request = $mockClient->getRequests()[0];

        $this->assertEquals("PATCH", $request->getMethod());
        $uri = $request->getUri();
        $this->assertEquals("", $uri->getQuery());
        $this->assertEquals("/v1/import/subscriptions/sub_some_id", $uri->getPath());
        $this->assertEquals("sub_65bc29a4-dbce-42a0-8435-d54b8701e762", $subscription->uuid);
        $this->assertEquals("pl_6a21613e-9c36-11e7-b772-cf778619abb9", $result->plan_uuid);
        $this->assertEquals("2018-08-08T00:00:00.000Z", $subscription->cancellation_dates[0]);
        $this->assertEquals("cus_69030f0a-9c36-11e7-997f-979f1762dcb8", $subscription->customer_uuid);
        $this->assertEquals("ds_51f40656-9b74-11e7-af6f-efb2fa0552a1", $subscription->data_source_uuid);
        $this->assertEquals("SB00004G8AMAWS", $subscription->external_id);
    }
}

