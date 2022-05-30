<?php
namespace ChartMogul\Tests;

use ChartMogul\Http\Client;
use GuzzleHttp\Psr7;
use GuzzleHttp\Psr7\Response;
use ChartMogul\Exceptions\ChartMogulException;
use ChartMogul\SubscriptionEvent;
use Doctrine\Common\Collections\ArrayCollection;

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
      	"amount_in_cents": 1000,
      	"tax_amount_in_cents": 19,
      	"retracted_event_id": null
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
      	"amount_in_cents": 1000,
      	"tax_amount_in_cents": 19,
      	"retracted_event_id": null
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
    "errors": {},
    "created_at": "2022-04-09T11:17:14Z",
    "updated_at": "2022-04-09T11:17:14Z",
    "quantity": 1,
    "currency": "USD",
    "amount_in_cents": 1000,
    "tax_amount_in_cents": 19,
    "retracted_event_id": null
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

     $customer_external_id = 'cus_0003';
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
     $stream = Psr7\stream_for(SubscriptionEventTest::RETRIEVE_SUBSCRIPTION_EVENT);
     list($cmClient, $mockClient) = $this->getMockClient(0, [200], $stream);

     $id = 73966836;

     $subscription_event = SubscriptionEvent::retrieve($id, $cmClient);

     $stream = Psr7\stream_for(SubscriptionEventTest::RETRIEVE_SUBSCRIPTION_EVENT);
     list($cmClient, $mockClient) = $this->getMockClient(0, [200], $stream);

     $data_source_uuid = "ds_1fm3eaac-62d0-31ec-clf4-4bf0mbe81aba";
     $external_id = "ex_id_1";

     $result = SubscriptionEvent::update(
       ["id" => $id,
       "data_source_uuid" => $data_source_uuid,
       "external_id" => $external_id],
       [
       "customer_external_id" => "scus_025"
       ],
       $cmClient
     );

     $request = $mockClient->getRequests()[0];
     $this->assertEquals("PATCH", $request->getMethod());
     $uri = $request->getUri();
     $this->assertEquals("", $uri->getQuery());
     $this->assertEquals("/v1/subscription_events/73966836", $uri->getPath());
     $this->assertTrue($result instanceof SubscriptionEvent);
     //$this->assertEquals($result->customer_external_id, "scus_025");
   }

   public function testDestroySubscriptionEvent()
   {
     list($cmClient, $mockClient) = $this->getMockClient(0, [204]);

     $result = (new SubscriptionEvent(["id" => "73966836"], $cmClient))->destroy();
     $request = $mockClient->getRequests()[0];

     $this->assertEquals("DELETE", $request->getMethod());
     $uri = $request->getUri();
     $this->assertEquals("", $uri->getQuery());
     //$this->assertEquals("/v1/subscription_events/73966836", $uri->getPath());
   }
}
