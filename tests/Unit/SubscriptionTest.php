<?php

use ChartMogul\Http\Client;
use ChartMogul\Subscription;
use ChartMogul\Exceptions\ChartMogulException;
use GuzzleHttp\Psr7;
use GuzzleHttp\Psr7\Response;

class SubscriptionTest extends \PHPUnit\Framework\TestCase
{

  const ALL_SUBS_JSON = '{
    "customer_uuid": "cus_f466e33d-ff2b-4a11-8f85-417eb02157a7",
    "subscriptions": [
        {
            "uuid": "sub_dd169c42-e127-4637-8b8f-a239b248e3cd",
            "external_id": "abc",
            "subscription_set_external_id": "set_001",
            "cancellation_dates": [],
            "plan_uuid": "pl_d6fe6904-8319-11e7-82b4-ffedd86c182a",
            "data_source_uuid": "ds_637442a6-8319-11e7-a280-1f28ec01465c"
        }
    ],
    "current_page": 2,
    "total_pages": 3
}';

    const CANCEL_SUBSCRIPTION = '{
      "cancellation_dates": ["2016-01-01T10:00:00.000Z", "2017-01-01T10:00:00.000Z"]
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
        $this->assertEquals("set_001", $result[0]->subscription_set_external_id);
        $this->assertEquals(2, $result->current_page);
        $this->assertEquals(3, $result->total_pages);
    }
    public function testCancel(){
        $stream = Psr7\stream_for(SubscriptionTest::CANCEL_SUBSCRIPTION);
        $response = new Response(200, ['Content-Type' => 'application/json'], $stream);
        $mockClient = new \Http\Mock\Client();
        $mockClient->addResponse($response);

        $cmClient = new Client(null, $mockClient);
        $subsUUID = "sub_e6bc5407-e258-4de0-bb43-61faaf062035";
        $subscription = new ChartMogul\Subscription(["uuid" => $subsUUID], $cmClient);
        $canceldate = '2016-01-01T10:00:00.000Z';
        $result = $subscription->cancel($canceldate);
        $request = $mockClient->getRequests()[0];

        $this->assertEquals("PATCH", $request->getMethod());
        $uri = $request->getUri();
        $this->assertEquals("", $uri->getQuery());
        $this->assertEquals("/v1/import/subscriptions/".$subsUUID, $uri->getPath());

        $this->assertTrue($result instanceof Subscription);
    }
    public function testConnect(){
        $stream = Psr7\stream_for('{}');
        $response = new Response(202, ['Content-Type' => 'application/json'], $stream);
        $mockClient = new \Http\Mock\Client();
        $mockClient->addResponse($response);

        $cmClient = new Client(null, $mockClient);
        $subscription1 = new Subscription(["external_id" => "d1c0c885-add0-48db-8fa9-0bdf5017d6b0", "data_source_uuid" => "ds_ade45e52-47a4-231a-1ed2-eb6b9e541213", "uuid" => "uuid_001"], $cmClient);
        $subscription2 = new Subscription(["external_id" => "9db5f4a1-1695-44c0-8bd4-de7ce4d0f1d4", "data_source_uuid" => "ds_ade45e52-47a4-231a-1ed2-eb6b9e541213"]);
        $subscription3 = ["external_id" => "sub_0001", "data_source_uuid" => "ds_ade45e52-47a4-231a-1ed2-eb6b9e541213"];
        $expected = [
            "subscriptions" => [
                [
                    "uuid" => "uuid_001",
                    "external_id" => "d1c0c885-add0-48db-8fa9-0bdf5017d6b0",
                    "data_source_uuid" => "ds_ade45e52-47a4-231a-1ed2-eb6b9e541213",
                ],
                [
                    "external_id" => "9db5f4a1-1695-44c0-8bd4-de7ce4d0f1d4",
                    "data_source_uuid" => "ds_ade45e52-47a4-231a-1ed2-eb6b9e541213",
                ],
                [
                    "external_id" => "sub_0001",
                    "data_source_uuid" => "ds_ade45e52-47a4-231a-1ed2-eb6b9e541213",
                ],
            ]
        ];

        $result = $subscription1->connect("cus_5915ee5a-babd-406b-b8ce-d207133fb4cb", [ 
            $subscription2, $subscription3,
        ]);
        $request = $mockClient->getRequests()[0];

        $this->assertEquals("POST", $request->getMethod());
        $uri = $request->getUri();
        $body = $request->getBody();
        $body->rewind();
        $this->assertEquals(json_encode($expected), $body->getContents());
        $this->assertEquals("", $uri->getQuery());
        $this->assertEquals("/v1/customers/cus_5915ee5a-babd-406b-b8ce-d207133fb4cb/connect_subscriptions", $uri->getPath());

        $this->assertEquals($result, true);
    }
}
