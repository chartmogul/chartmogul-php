<?php
namespace ChartMogul\Tests;

use ChartMogul\Http\Client;
use ChartMogul\Plan;
use ChartMogul\Exceptions\ChartMogulException;
use ChartMogul\Resource\CollectionWithCursor;
use GuzzleHttp\Psr7;
use GuzzleHttp\Psr7\Response;
use ChartMogul\Exceptions\NotFoundException;

class PlanTest extends TestCase
{
    const ALL_PLANS_JSON = '{
      "plans": [{
        "name": "My plan",
        "uuid": "pl_93756449-aaea-4662-9e3a-004e52f14adc",
        "interval_count": 1,
        "interval_unit": "month",
        "data_source_uuid": "ds_d02c7c42-2d1d-11ee-8e58-3fa5919351b4",
        "external_id": "plan_001"
      }],
      "cursor": "cursor==",
      "has_more": false
    }';
    const RETRIEVE_PLAN = '{
      "name": "My plan",
      "uuid": "pl_93756449-aaea-4662-9e3a-004e52f14adc",
      "interval_count": 1,
      "interval_unit": "month",
      "data_source_uuid": "ds_d02c7c42-2d1d-11ee-8e58-3fa5919351b4",
      "external_id": "plan_001"
    }';
    const UPDATED_PLAN = '{
      "name": "My awesome plan",
      "uuid": "pl_93756449-aaea-4662-9e3a-004e52f14adc",
      "interval_count": 3,
      "interval_unit": "month",
      "data_source_uuid": "ds_d02c7c42-2d1d-11ee-8e58-3fa5919351b4",
      "external_id": "plan_001"
    }';

    public function testAllPlans()
    {
      $stream = Psr7\stream_for(PlanTest::ALL_PLANS_JSON);
      list($cmClient, $mockClient) = $this->getMockClient(0, [200], $stream);

      $query = ["page" => 2, "per_page" => 1];

      $result = Plan::all($query, $cmClient);
      $request = $mockClient->getRequests()[0];

      $this->assertEquals("GET", $request->getMethod());
      $uri = $request->getUri();
      $this->assertEquals("page=2&per_page=1", $uri->getQuery());
      $this->assertEquals("/v1/plans", $uri->getPath());

      $this->assertTrue($result instanceof CollectionWithCursor);
      $this->assertEquals(1, sizeof($result));
      $this->assertTrue($result[0] instanceof Plan);
      $this->assertEquals("pl_93756449-aaea-4662-9e3a-004e52f14adc", $result[0]->uuid);
      $this->assertEquals("cursor==", $result->cursor);
      $this->assertEquals(false, $result->has_more);
    }

    public function testDestroyPlan()
    {
      list($cmClient, $mockClient) = $this->getMockClient(0, [204]);

      $result = (new Plan(["uuid" => "pl_93756449-aaea-4662-9e3a-004e52f14adc"], $cmClient))->destroy();
      $request = $mockClient->getRequests()[0];

      $this->assertEquals("DELETE", $request->getMethod());
      $uri = $request->getUri();
      $this->assertEquals("", $uri->getQuery());
      $this->assertEquals("/v1/plans/pl_93756449-aaea-4662-9e3a-004e52f14adc", $uri->getPath());
    }

    public function testDestroyPlanNotFound()
    {
        list($cmClient, $mockClient) = $this->getMockClient(0, [404]);
        $this->expectException(NotFoundException::class);

        $result = (new Plan(["uuid" => "pl_93756449-aaea-4662-9e3a-004e52f14adc"], $cmClient))->destroy();
    }

    public function testRetrievePlan()
    {
        $stream = Psr7\stream_for(PlanTest::RETRIEVE_PLAN);
        list($cmClient, $mockClient) = $this->getMockClient(0, [200], $stream);

        $uuid = "pl_93756449-aaea-4662-9e3a-004e52f14adc";

        $result = Plan::retrieve($uuid, $cmClient);
        $request = $mockClient->getRequests()[0];

        $this->assertEquals("GET", $request->getMethod());
        $uri = $request->getUri();
        $this->assertEquals("", $uri->getQuery());
        $this->assertEquals("/v1/plans/".$uuid, $uri->getPath());

        $this->assertTrue($result instanceof Plan);
        $this->assertEquals($uuid, $result->uuid);
    }

    public function testCreatePlan()
    {
      $stream = Psr7\stream_for(PlanTest::RETRIEVE_PLAN);
      list($cmClient, $mockClient) = $this->getMockClient(0, [201], $stream);

      $result = Plan::create([
          "name" => "My Plan",
          "data_source_uuid" => "ds_d02c7c42-2d1d-11ee-8e58-3fa5919351b4",
          "interval_count" => 1,
          "interval_unit" => "month",
          "external_id" => "plan_001"
      ],
      $cmClient
      );

      $request = $mockClient->getRequests()[0];

      $this->assertEquals("POST", $request->getMethod());
      $uri = $request->getUri();
      $this->assertEquals("", $uri->getQuery());
      $this->assertEquals("/v1/plans", $uri->getPath());

      $this->assertTrue($result instanceof Plan);
      $this->assertEquals("pl_93756449-aaea-4662-9e3a-004e52f14adc", $result->uuid);
    }

    public function testUpdatePlan()
    {
      $stream = Psr7\stream_for(PlanTest::UPDATED_PLAN);
      list($cmClient, $mockClient) = $this->getMockClient(0, [200], $stream);

      $uuid = "pl_93756449-aaea-4662-9e3a-004e52f14adc";

      $result = Plan::update(
        ["plan_uuid" => $uuid],
        [
            "name" => "My awesome plan",
            "interval_count" => 3
        ],
        $cmClient
      );
      $request = $mockClient->getRequests()[0];

      $this->assertEquals("PATCH", $request->getMethod());
      $uri = $request->getUri();
      $this->assertEquals("", $uri->getQuery());
      $this->assertEquals("/v1/plans/pl_93756449-aaea-4662-9e3a-004e52f14adc", $uri->getPath());
      $this->assertTrue($result instanceof Plan);
      $this->assertEquals($result->name, "My awesome plan");
      $this->assertEquals($result->interval_count, 3);
    }
}
