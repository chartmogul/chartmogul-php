<?php

use ChartMogul\Http\Client;
use ChartMogul\PlanGroups\Plan;
use GuzzleHttp\Psr7;
use GuzzleHttp\Psr7\Response;

class PlanGroupsPlanTest extends \PHPUnit\Framework\TestCase
{
    const ALL_PLAN_GROUPS_PLANS_JSON = '{
    "plans": [
      {
        "name": "A Test Plan",
        "uuid": "pl_2f7f4360-3633-0138-f01f-62b37fb4c770",
        "data_source_uuid":"ds_424b9628-5405-11ea-ab18-e3a0f4f45097",
        "interval_count":1,
        "interval_unit":"month",
        "external_id":"2f7f4360-3633-0138-f01f-62b37fb4c770"
      },
      {
        "name":"Another Test Plan",
        "uuid": "pl_3011ead0-3633-0138-f020-62b37fb4c770",
        "data_source_uuid": "ds_424b9628-5405-11ea-ab18-e3a0f4f45097",
        "interval_count": 1,
        "interval_unit": "month",
        "external_id": "3011ead0-3633-0138-f020-62b37fb4c770"
      }
    ],
    "current_page": 1,
    "total_pages": 1
  }';

    public function testAllPlanGroups()
    {
      $stream = Psr7\stream_for(PlanGroupsPlanTest::ALL_PLAN_GROUPS_PLANS_JSON);
      $response = new Response(200, ['Content-Type' => 'application/json'], $stream);
      $mockClient = new \Http\Mock\Client();
      $mockClient->addResponse($response);

      $cmClient = new Client(null, $mockClient);
      $planGroupUuid = 'plg_b53fdbfc-c5eb-4a61-a589-85146cf8d0ab';
      $query = [
        "plan_group_uuid" => $planGroupUuid,
        "page" => 1,
        "per_page" => 2
      ];

      $result = Plan::all($query, $cmClient);
      $request = $mockClient->getRequests()[0];

      $this->assertEquals("GET", $request->getMethod());
      $uri = $request->getUri();
      $this->assertEquals("/v1/plan_groups/$planGroupUuid/plans", $uri->getPath());
      $this->assertEquals("page=1&per_page=2", $uri->getQuery());

      $this->assertEquals(2, sizeof($result));
      $this->assertTrue($result[0] instanceof ChartMogul\PlanGroups\Plan);
      $this->assertEquals("pl_2f7f4360-3633-0138-f01f-62b37fb4c770", $result[0]->uuid);
      $this->assertEquals("ds_424b9628-5405-11ea-ab18-e3a0f4f45097", $result[0]->data_source_uuid);
      $this->assertEquals(1, $result->current_page);
      $this->assertEquals(1, $result->total_pages);
    }
}
