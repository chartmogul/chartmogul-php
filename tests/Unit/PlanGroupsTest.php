<?php
namespace ChartMogul\Tests;

use ChartMogul\Http\Client;
use ChartMogul\PlanGroup;
use ChartMogul\Exceptions\ChartMogulException;
use GuzzleHttp\Psr7;
use GuzzleHttp\Psr7\Response;
use ChartMogul\Exceptions\NotFoundException;

class PlanGroupTest extends TestCase
{
    const ALL_PLAN_GROUPS_JSON = '{
      "plan_groups": [{
        "name": "My plan group",
        "uuid": "plg_b53fdbfc-c5eb-4a61-a589-85146cf8d0ab",
        "plans_count": 2
      }],
      "cursor": "cursor==",
      "has_more": false
    }';
    const RETRIEVE_PLAN_GROUP = '{
      "name": "My plan group",
      "uuid": "plg_b53fdbfc-c5eb-4a61-a589-85146cf8d0ab",
      "plans_count": 2
    }';

    public function testAllPlanGroups()
    {
        $stream = Psr7\stream_for(PlanGroupTest::ALL_PLAN_GROUPS_JSON);
        list($cmClient, $mockClient) = $this->getMockClient(0, [200], $stream);

        $query = ["per_page" => 1];
        $result = PlanGroup::all($query, $cmClient);
        $request = $mockClient->getRequests()[0];

        $this->assertEquals("GET", $request->getMethod());
        $uri = $request->getUri();
        $this->assertEquals("per_page=1", $uri->getQuery());
        $this->assertEquals("/v1/plan_groups", $uri->getPath());

        $this->assertEquals(1, sizeof($result));
        $this->assertTrue($result[0] instanceof PlanGroup);
        $this->assertEquals("plg_b53fdbfc-c5eb-4a61-a589-85146cf8d0ab", $result[0]->uuid);
        $this->assertEquals("cursor==", $result->cursor);
        $this->assertFalse($result->has_more);
    }

    public function testAllPlanGroupsDeprecatedPagination()
    {
        $stream = Psr7\stream_for(PlanGroupTest::ALL_PLAN_GROUPS_JSON);
        list($cmClient, $mockClient) = $this->getMockClientException(
          0, [200], $stream, [\ChartMogul\Exceptions\DeprecatedParameterException::class]
        );

        $query = ["page" => 2, "per_page" => 1];
        $result = PlanGroup::all($query, $cmClient);
    }

    public function testDestroyPlanGroup()
    {
        list($cmClient, $mockClient) = $this->getMockClient(0, [204]);

        $result = (new PlanGroup(["uuid" => "plg_b53fdbfc-c5eb-4a61-a589-85146cf8d0ab"], $cmClient))->destroy();
        $request = $mockClient->getRequests()[0];

        $this->assertEquals("DELETE", $request->getMethod());
        $uri = $request->getUri();
        $this->assertEquals("", $uri->getQuery());
        $this->assertEquals("/v1/plan_groups/plg_b53fdbfc-c5eb-4a61-a589-85146cf8d0ab", $uri->getPath());
    }

    public function testDestroyPlanGroupNotFound()
    {
        list($cmClient, $mockClient) = $this->getMockClient(0, [404]);
        $this->expectException(NotFoundException::class);

        $result = (new PlanGroup(["uuid" => "plg_b53fdbfc-c5eb-4a61-a589-85146cf8d0ab"], $cmClient))->destroy();
    }

    public function testRetrievePlanGroup()
    {
        $stream = Psr7\stream_for(PlanGroupTest::RETRIEVE_PLAN_GROUP);
        list($cmClient, $mockClient) = $this->getMockClient(0, [200], $stream);

        $uuid = 'plg_b53fdbfc-c5eb-4a61-a589-85146cf8d0ab';

        $result = PlanGroup::retrieve($uuid, $cmClient);
        $request = $mockClient->getRequests()[0];

        $this->assertEquals("GET", $request->getMethod());
        $uri = $request->getUri();
        $this->assertEquals("", $uri->getQuery());
        $this->assertEquals("/v1/plan_groups/".$uuid, $uri->getPath());

        $this->assertTrue($result instanceof PlanGroup);
        $this->assertEquals($uuid, $result->uuid);
    }

    public function testCreatePlanGroup()
    {
        $stream = Psr7\stream_for(PlanGroupTest::RETRIEVE_PLAN_GROUP);
        list($cmClient, $mockClient) = $this->getMockClient(0, [200], $stream);

        $plan_uuid_1 = 'pl_7e7c1bc7-50e0-447d-9750-8d66e9c0c702';
        $plan_uuid_2 = 'pl_2d1ca933-8745-43d9-a836-85674597699c';


        $result = PlanGroup::create(
            [
            "name" => "My Plan Group",
            "plans" => [$plan_uuid_1, $plan_uuid_2],
            ],
            $cmClient
        );

        $request = $mockClient->getRequests()[0];

        $this->assertEquals("POST", $request->getMethod());
        $uri = $request->getUri();
        $this->assertEquals("", $uri->getQuery());
        $this->assertEquals("/v1/plan_groups", $uri->getPath());

        $this->assertTrue($result instanceof PlanGroup);
        $this->assertEquals("plg_b53fdbfc-c5eb-4a61-a589-85146cf8d0ab", $result->uuid);
    }

    public function testUpdatePlanGroup()
    {
        $stream = Psr7\stream_for(PlanGroupTest::RETRIEVE_PLAN_GROUP);
        list($cmClient, $mockClient) = $this->getMockClient(0, [200], $stream);

        $uuid = 'plg_b53fdbfc-c5eb-4a61-a589-85146cf8d0ab';

        $plan_group = PlanGroup::retrieve($uuid, $cmClient);

        $stream = Psr7\stream_for(PlanGroupTest::RETRIEVE_PLAN_GROUP);
        list($cmClient, $mockClient) = $this->getMockClient(0, [200], $stream);

        $uuid = 'plg_b53fdbfc-c5eb-4a61-a589-85146cf8d0ab';
        $plan_uuid_1 = 'pl_7e7c1bc7-50e0-447d-9750-8d66e9c0c702';
        $plan_uuid_2 = 'pl_2d1ca933-8745-43d9-a836-85674597699c';

        $result = PlanGroup::update(
            ["plan_group_uuid" => $uuid],
            [
            "name" => "My plan group",
            "plans" => [$plan_uuid_1, $plan_uuid_2]
            ],
            $cmClient
        );

        $request = $mockClient->getRequests()[0];
        $this->assertEquals("PATCH", $request->getMethod());
        $uri = $request->getUri();
        $this->assertEquals("", $uri->getQuery());
        $this->assertEquals("/v1/plan_groups/plg_b53fdbfc-c5eb-4a61-a589-85146cf8d0ab", $uri->getPath());
        $this->assertTrue($result instanceof PlanGroup);
        $this->assertEquals($result->name, "My plan group");
        $this->assertEquals($result->plans_count, 2);
    }
}
