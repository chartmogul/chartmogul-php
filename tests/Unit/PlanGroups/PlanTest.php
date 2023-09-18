<?php
namespace ChartMogul\Tests;

use ChartMogul\Http\Client;
use ChartMogul\PlanGroups\Plan;
use ChartMogul\Resource\CollectionWithCursor;
use GuzzleHttp\Psr7;
use GuzzleHttp\Psr7\Response;

class PlanGroupsPlanTest extends TestCase
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
    "cursor": "cursor==",
    "has_more": false
  }';

    public function testAllPlanGroups()
    {
      $stream = Psr7\stream_for(PlanGroupsPlanTest::ALL_PLAN_GROUPS_PLANS_JSON);
      list($cmClient, $mockClient) = $this->getMockClient(0, [200], $stream);

      $planGroupUuid = 'plg_b53fdbfc-c5eb-4a61-a589-85146cf8d0ab';
      $query = [
        "plan_group_uuid" => $planGroupUuid,
        "per_page" => 2
      ];

      $result = Plan::all($query, $cmClient);
      $request = $mockClient->getRequests()[0];

      $this->assertEquals("GET", $request->getMethod());
      $uri = $request->getUri();
      $this->assertEquals("/v1/plan_groups/$planGroupUuid/plans", $uri->getPath());
      $this->assertEquals("per_page=2", $uri->getQuery());

      $this->assertTrue($result instanceof CollectionWithCursor);
      $this->assertEquals(2, sizeof($result));
      $this->assertTrue($result[0] instanceof Plan);
      $this->assertEquals("pl_2f7f4360-3633-0138-f01f-62b37fb4c770", $result[0]->uuid);
      $this->assertEquals("ds_424b9628-5405-11ea-ab18-e3a0f4f45097", $result[0]->data_source_uuid);
      $this->assertEquals("cursor==", $result->cursor);
      $this->assertEquals(false, $result->has_more);
    }
}
