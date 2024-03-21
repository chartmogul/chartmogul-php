<?php
namespace ChartMogul\Tests;

use ChartMogul\Http\Client;
use ChartMogul\Opportunity;
use ChartMogul\Resource\Collection;
use ChartMogul\Exceptions\ChartMogulException;
use GuzzleHttp\Psr7;
use GuzzleHttp\Psr7\Response;

class OpportunityTest extends TestCase
{
    const OPPORTUNITY_JSON= '{
      "uuid": "00000000-0000-0000-0000-000000000000",
      "customer_uuid": "cus_00000000-0000-0000-0000-000000000000",
      "owner": "test1@example.org",
      "pipeline": "New business 1",
      "pipeline_stage": "Discovery",
      "estimated_close_date": "2023-12-22",
      "currency": "USD",
      "amount_in_cents": 100,
      "type": "recurring",
      "forecast_category": "pipeline",
      "win_likelihood": 3,
      "custom": {"from_campaign": true},
      "created_at": "2024-03-13T07:33:28.356Z",
      "updated_at": "2024-03-13T07:33:28.356Z"
    }';

    const UPDATED_OPPORTUNITY_JSON= '{
      "uuid": "00000000-0000-0000-0000-000000000000",
      "customer_uuid": "cus_00000000-0000-0000-0000-000000000000",
      "owner": "test1@example.org",
      "pipeline": "New business 1",
      "pipeline_stage": "Discovery",
      "estimated_close_date": "2024-12-22",
      "currency": "USD",
      "amount_in_cents": 100,
      "type": "recurring",
      "forecast_category": "pipeline",
      "win_likelihood": 3,
      "custom": {"from_campaign": true},
      "created_at": "2024-03-13T07:33:28.356Z",
      "updated_at": "2024-03-14T07:33:28.356Z"
    }';

    const LIST_OPPORTUNITIES_JSON= '{
        "entries": [
          {
            "uuid": "00000000-0000-0000-0000-000000000000",
            "customer_uuid": "cus_00000000-0000-0000-0000-000000000000",
            "owner": "test1@example.org",
            "pipeline": "New business 1",
            "pipeline_stage": "Discovery",
            "estimated_close_date": "2023-12-22",
            "currency": "USD",
            "amount_in_cents": 100,
            "type": "recurring",
            "forecast_category": "pipeline",
            "win_likelihood": 3,
            "custom": {"from_campaign": true},
            "created_at": "2024-03-13T07:33:28.356Z",
            "updated_at": "2024-03-13T07:33:28.356Z"
          }
        ],
        "cursor": "cursor==",
        "has_more": true
      }';

    public function testListOpportunities()
    {
        $stream = Psr7\stream_for(OpportunityTest::LIST_OPPORTUNITIES_JSON);
        list($cmClient, $mockClient) = $this->getMockClient(0, [200], $stream);

        $customer_uuid = "cus_00000000-0000-0000-0000-000000000000";

        $result = Opportunity::all(["customer_uuid" => $customer_uuid], $cmClient);
        $request = $mockClient->getRequests()[0];

        $this->assertEquals("GET", $request->getMethod());
        $uri = $request->getUri();
        $this->assertEquals("/v1/opportunities", $uri->getPath());

        $this->assertTrue($result[0] instanceof Opportunity);
        $this->assertEquals("cursor==", $result->cursor);
        $this->assertEquals(true, $result->has_more);
    }

    public function testCreateOpportunity()
    {
        $stream = Psr7\stream_for(OpportunityTest::OPPORTUNITY_JSON);
        list($cmClient, $mockClient) = $this->getMockClient(0, [200], $stream);

        $customer_uuid = "cus_00000000-0000-0000-0000-000000000000";

        $result = Opportunity::create(
            [
              "customer_uuid" => $customer_uuid,
              "owner" => "test1@example.org",
              "pipeline" => "New business 1",
              "pipeline_stage" => "Discovery",
              "estimated_close_date" => "2023-12-22",
              "currency" => "USD",
              "amount_in_cents" => 100,
              "type" => "recurring",
              "forecast_category" => "pipeline",
              "win_likelihood" => 3,
              "custom" => [
                [ "key" => "from_campaign", "value" => true ],
              ]
            ]
            , $cmClient
        );
        $request = $mockClient->getRequests()[0];

        $this->assertEquals("POST", $request->getMethod());
        $uri = $request->getUri();
        $this->assertEquals("/v1/opportunities", $uri->getPath());

        $this->assertTrue($result instanceof Opportunity);
        $this->assertEquals("00000000-0000-0000-0000-000000000000", $result->uuid);
        $this->assertEquals("cus_00000000-0000-0000-0000-000000000000", $result->customer_uuid);
        $this->assertEquals("test1@example.org", $result->owner);
        $this->assertEquals("New business 1", $result->pipeline);
        $this->assertEquals("Discovery", $result->pipeline_stage);
        $this->assertEquals("2023-12-22", $result->estimated_close_date);
        $this->assertEquals("USD", $result->currency);
        $this->assertEquals(100, $result->amount_in_cents);
        $this->assertEquals("recurring", $result->type);
        $this->assertEquals("pipeline", $result->forecast_category);
        $this->assertEquals(3, $result->win_likelihood);
        $this->assertEquals(["from_campaign" => True], $result->custom);
        $this->assertEquals("2024-03-13T07:33:28.356Z", $result->created_at);
        $this->assertEquals("2024-03-13T07:33:28.356Z", $result->updated_at);
    }

    public function testRetrieveOpportunity()
    {
        $stream = Psr7\stream_for(OpportunityTest::OPPORTUNITY_JSON);
        list($cmClient, $mockClient) = $this->getMockClient(0, [200], $stream);

        $uuid = "00000000-0000-0000-0000-000000000000";

        $result = Opportunity::retrieve($uuid, $cmClient);
        $request = $mockClient->getRequests()[0];

        $this->assertEquals("GET", $request->getMethod());
        $uri = $request->getUri();
        $this->assertEquals("/v1/opportunities/".$uuid, $uri->getPath());

        $this->assertTrue($result instanceof Opportunity);
        $this->assertEquals("00000000-0000-0000-0000-000000000000", $result->uuid);
        $this->assertEquals("cus_00000000-0000-0000-0000-000000000000", $result->customer_uuid);
        $this->assertEquals("test1@example.org", $result->owner);
        $this->assertEquals("New business 1", $result->pipeline);
        $this->assertEquals("Discovery", $result->pipeline_stage);
        $this->assertEquals("2023-12-22", $result->estimated_close_date);
        $this->assertEquals("USD", $result->currency);
        $this->assertEquals(100, $result->amount_in_cents);
        $this->assertEquals("recurring", $result->type);
        $this->assertEquals("pipeline", $result->forecast_category);
        $this->assertEquals(3, $result->win_likelihood);
        $this->assertEquals(["from_campaign" => True], $result->custom);
        $this->assertEquals("2024-03-13T07:33:28.356Z", $result->created_at);
        $this->assertEquals("2024-03-13T07:33:28.356Z", $result->updated_at);
    }

    public function testUpdateOpportunity()
    {
        $stream = Psr7\stream_for(OpportunityTest::UPDATED_OPPORTUNITY_JSON);
        list($cmClient, $mockClient) = $this->getMockClient(0, [200], $stream);

        $uuid = "00000000-0000-0000-0000-000000000000";

        $result = Opportunity::update(
            [
            "uuid" => $uuid,
            ], [
              "estimated_close_date" => "2024-12-22",
            ], $cmClient
        );
        $request = $mockClient->getRequests()[0];

        $this->assertEquals("PATCH", $request->getMethod());
        $uri = $request->getUri();
        $this->assertEquals("/v1/opportunities/".$uuid, $uri->getPath());

        $this->assertTrue($result instanceof Opportunity);
        $this->assertEquals("2024-12-22", $result->estimated_close_date);
    }

    public function testDeleteOpportunity()
    {
        $stream = Psr7\stream_for("{}");
        list($cmClient, $mockClient) = $this->getMockClient(0, [204], $stream);

        $uuid = "00000000-0000-0000-0000-000000000000";

        $result = (new Opportunity(["uuid" => $uuid], $cmClient))->destroy();
        $request = $mockClient->getRequests()[0];

        $this->assertEquals("DELETE", $request->getMethod());
        $uri = $request->getUri();
        $this->assertEquals("/v1/opportunities/".$uuid, $uri->getPath());

        $this->assertEquals("{}", $result);
    }
}
