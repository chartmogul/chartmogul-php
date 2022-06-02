<?php
namespace ChartMogul\Tests;

use ChartMogul\Http\Client;
use ChartMogul\SubscriptionEvent;
use GuzzleHttp\Psr7;
use GuzzleHttp\Psr7\Response;

class SubscriptionEventTest extends TestCase
{
    const ALL_SUBSCRIPTION_EVENT_JSON = '{
    "subscription_events": [
      {
        "id": 73966836,
      	"data_source_uuid": "ds_1fm3eaac-62d0-31ec-clf4-4bf0mbe81aba",
      	"customer_external_id": "scus_023",
      	"subscription_set_external_id": "sub_set_ex_id_1",
      	"subscription_external_id": "sub_0023",
      	"plan_external_id": "p_ex_id_1",
      	"event_date": "2022-04-09T11:17:14Z",
      	"effective_date": "2022-04-09T10:04:13Z",
      	"event_type": "subscription_cancelled",
      	"external_id": "ex_id_1",
      	"errors": {},
      	"created_at": "2022-04-09T11:17:14Z",
      	"updated_at": "2022-04-09T11:17:14Z",
      	"quantity": 1,
      	"currency": "USD",
      	"amount_in_cents": 1000
      },
      {
        "id": 73966837,
      	"data_source_uuid": "ds_1fm3eaac-62d0-31ec-clf4-4bf0mbe81aba",
      	"customer_external_id": "scus_024",
      	"subscription_set_external_id": "sub_set_ex_id_2",
      	"subscription_external_id": "sub_0024",
      	"plan_external_id": "p_ex_id_2",
      	"event_date": "2022-04-09T11:17:14Z",
      	"effective_date": "2022-04-09T10:04:13Z",
      	"event_type": "subscription_cancelled",
      	"external_id": "ex_id_2",
      	"errors": {},
      	"created_at": "2022-04-09T11:17:14Z",
      	"updated_at": "2022-04-09T11:17:14Z",
      	"quantity": 1,
      	"currency": "USD",
      	"amount_in_cents": 1000
      }
    ],
    "current_page": 1,
    "total_pages": 1
  }';
  const RETRIEVE_SUBSCRIPTION_EVENT = '{
    "id": 73966836,
    "data_source_uuid": "ds_1fm3eaac-62d0-31ec-clf4-4bf0mbe81aba",
    "customer_external_id": "scus_023",
    "subscription_set_external_id": "sub_set_ex_id_1",
    "subscription_external_id": "sub_0023",
    "plan_external_id": "p_ex_id_1",
    "event_date": "2022-04-09T11:17:14Z",
    "effective_date": "2022-04-09T10:04:13Z",
    "event_type": "subscription_cancelled",
    "external_id": "ex_id_1",
    "created_at": "2022-04-09T11:17:14Z",
    "updated_at": "2022-04-09T11:17:14Z",
    "quantity": 1,
    "currency": "USD",
    "amount_in_cents": 1000
  }';

  const UPDATE_SUBSCRIPTION_EVENT = '{
    "id": 73966836,
    "data_source_uuid": "ds_1fm3eaac-62d0-31ec-clf4-4bf0mbe81aba",
    "customer_external_id": "scus_023",
    "subscription_set_external_id": "sub_set_ex_id_1",
    "subscription_external_id": "sub_0023",
    "plan_external_id": "p_ex_id_1",
    "event_date": "2022-04-09T11:17:14Z",
    "effective_date": "2022-04-09T10:04:13Z",
    "event_type": "subscription_cancelled",
    "external_id": "ex_id_1",
    "created_at": "2022-04-09T11:17:14Z",
    "updated_at": "2022-04-09T11:17:14Z",
    "quantity": 1,
    "currency": "EUR",
    "amount_in_cents": 100
  }';

   public function testAllSubscriptionEvents()
   {
     $stream = Psr7\stream_for(SubscriptionEventTest::ALL_SUBSCRIPTION_EVENT_JSON);
     list($cmClient, $mockClient) = $this->getMockClient(0, [200], $stream);

     $query = ["page" => 1, "external_id" => "ex_id_1"];
     $result = SubscriptionEvent::all($query, $cmClient);
     $request = $mockClient->getRequests()[0];

     $this->assertEquals("GET", $request->getMethod());
     $uri = $request->getUri();
     $this->assertEquals("page=1&external_id=ex_id_1", $uri->getQuery());
     $this->assertEquals("/v1/subscription_events", $uri->getPath());

     $this->assertEquals(2, sizeof($result));
     $this->assertTrue($result[0] instanceof SubscriptionEvent);
     $this->assertEquals("ds_1fm3eaac-62d0-31ec-clf4-4bf0mbe81aba", $result[0]->data_source_uuid);
     $this->assertEquals("scus_023", $result[0]->customer_external_id);
     $this->assertEquals(1, $result->current_page);
     $this->assertEquals(1, $result->total_pages);
   }

   public function testCreateSubscriptionEvent()
   {
     $stream = Psr7\stream_for(SubscriptionEventTest::RETRIEVE_SUBSCRIPTION_EVENT);
     list($cmClient, $mockClient) = $this->getMockClient(0, [200], $stream);

     $customer_external_id = 'scus_023';
     $data_source_uuid = "ds_1fm3eaac-62d0-31ec-clf4-4bf0mbe81aba";
     $event_type = 'subscription_start_scheduled';
     $event_date = '2022-03-30';
     $effective_date = '2022-04-01';

     $result = SubscriptionEvent::create([
        "customer_external_id" => $customer_external_id,
        "data_source_uuid" => $data_source_uuid,
        "event_type" => $event_type,
        "event_date" => $event_date,
        "effective_date" => $effective_date,
     ],
     $cmClient
     );

     $request = $mockClient->getRequests()[0];

     $this->assertEquals("POST", $request->getMethod());
     $uri = $request->getUri();
     $this->assertEquals("", $uri->getQuery());
     $this->assertEquals("/v1/subscription_events", $uri->getPath());

     $this->assertTrue($result instanceof SubscriptionEvent);
     $this->assertEquals("scus_023", $result->customer_external_id);
   }

   public function testUpdateSubscriptionEvent()
   {
     $stream = Psr7\stream_for(SubscriptionEventTest::UPDATE_SUBSCRIPTION_EVENT);
     list($cmClient, $mockClient) = $this->getMockClient(0, [200], $stream);

     $id = 73966836;
     $new_amount = 100;

     $result = SubscriptionEvent::update(
       ["id" => $id],
       ['amount_in_cents' => $new_amount],
       $cmClient
     );

     $request = $mockClient->getRequests()[0];
     $this->assertEquals("PATCH", $request->getMethod());
     $uri = $request->getUri();
     $this->assertEquals("", $uri->getQuery());
     $this->assertEquals("/v1/subscription_events", $uri->getPath());
     $this->assertTrue($result instanceof SubscriptionEvent);
     $this->assertEquals($result->amount_in_cents, $new_amount);
   }

   public function testDestroySubscriptionEventWithId()
   {
     list($cmClient, $mockClient) = $this->getMockClient(0, [204]);
     $id = 73966836;

     $result = (new SubscriptionEvent(["id" => $id], $cmClient))->destroy([$id]);
     $request = $mockClient->getRequests()[0];
     $this->assertEquals("DELETE", $request->getMethod());
     $uri = $request->getUri();
     $this->assertEquals("", $uri->getQuery());
     $this->assertEquals("/v1/subscription_events", $uri->getPath());
   }

   public function testDestroySubscriptionEventWithDataSourceUuidAndExternalId()
      {
        list($cmClient, $mockClient) = $this->getMockClient(0, [204]);
        $data_source_uuid = "ds_1fm3eaac-62d0-31ec-clf4-4bf0mbe81aba";
        $external_id = "ex_id_1";
        $id = 73966836;

        $result = (new SubscriptionEvent(["id" => $id], $cmClient))->destroy([$data_source_uuid, $external_id]);
        $request = $mockClient->getRequests()[0];
        $this->assertEquals("DELETE", $request->getMethod());
        $uri = $request->getUri();
        $this->assertEquals("", $uri->getQuery());
        $this->assertEquals("/v1/subscription_events", $uri->getPath());
      }
}
