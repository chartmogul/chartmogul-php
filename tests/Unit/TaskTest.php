<?php
namespace ChartMogul\Tests;

use ChartMogul\Http\Client;
use ChartMogul\Task;
use ChartMogul\Resource\Collection;
use ChartMogul\Exceptions\ChartMogulException;
use GuzzleHttp\Psr7;
use GuzzleHttp\Psr7\Response;

class TaskTest extends TestCase
{
    const TASK_JSON = '{
      "uuid": "00000000-0000-0000-0000-000000000000",
      "customer_uuid": "cus_00000000-0000-0000-0000-000000000000",
      "task_details": "This is some task details text.",
      "assignee": "customer@example.com",
      "due_date": "2025-04-30T00:00:00Z",
      "completed_at": "2025-04-20T00:00:00Z",
      "created_at": "2025-04-01T12:00:00.000Z",
      "updated_at": "2025-04-01T12:00:00.000Z"
    }';

    const UPDATED_TASK_JSON= '{
      "uuid": "00000000-0000-0000-0000-000000000000",
      "customer_uuid": "cus_00000000-0000-0000-0000-000000000000",
      "task_details": "This is some other task details text.",
      "assignee": "customer@example.com",
      "due_date": "2025-04-30T00:00:00Z",
      "completed_at": "2025-04-20T00:00:00Z",
      "created_at": "2025-04-01T12:00:00.000Z",
      "updated_at": "2025-04-01T12:00:00.000Z"
    }';

    const LIST_TASKS_JSON = '{
      "entries": [
        {
          "uuid": "00000000-0000-0000-0000-000000000000",
          "customer_uuid": "cus_00000000-0000-0000-0000-000000000000",
          "task_details": "This is some task details text.",
          "assignee": "customer@example.com",
          "due_date": "2025-04-30T00:00:00Z",
          "completed_at": "2025-04-20T00:00:00Z",
          "created_at": "2025-04-01T12:00:00.000Z",
          "updated_at": "2025-04-01T12:00:00.000Z"
        }
      ],
      "cursor": "cursor==",
      "has_more": true
    }';

    public function testListTasks()
    {
        $stream = Psr7\stream_for(TaskTest::LIST_TASKS_JSON);
        list($cmClient, $mockClient) = $this->getMockClient(0, [200], $stream);

        $customer_uuid = "cus_00000000-0000-0000-0000-000000000000";

        $result = Task::all(["customer_uuid" => $customer_uuid], $cmClient);
        $request = $mockClient->getRequests()[0];

        $this->assertEquals("GET", $request->getMethod());
        $uri = $request->getUri();
        $this->assertEquals("/v1/tasks", $uri->getPath());

        $this->assertTrue($result[0] instanceof Task);
        $this->assertEquals("cursor==", $result->cursor);
        $this->assertEquals(true, $result->has_more);
    }

    public function testCreateTask()
    {
        $stream = Psr7\stream_for(TaskTest::TASK_JSON);
        list($cmClient, $mockClient) = $this->getMockClient(0, [200], $stream);

        $customer_uuid = "cus_00000000-0000-0000-0000-000000000000";

        $result = Task::create(
            [
            "customer_uuid" => $customer_uuid,
            "task_details" => "This is some task details text.",
            "assignee" => "customer@example.com",
            "due_date" => "2025-04-30T00:00:00Z",
            "completed_at" => "2025-04-20T00:00:00Z",
            ]
            , $cmClient
        );
        $request = $mockClient->getRequests()[0];

        $this->assertEquals("POST", $request->getMethod());
        $uri = $request->getUri();
        $this->assertEquals("/v1/tasks", $uri->getPath());

        $this->assertTrue($result instanceof Task);
        $this->assertEquals("00000000-0000-0000-0000-000000000000", $result->uuid);
        $this->assertEquals("cus_00000000-0000-0000-0000-000000000000", $result->customer_uuid);
        $this->assertEquals("This is some task details text.", $result->task_details);
        $this->assertEquals("customer@example.com", $result->assignee);
        $this->assertEquals("2025-04-30T00:00:00Z", $result->due_date);
        $this->assertEquals("2025-04-20T00:00:00Z", $result->completed_at);
        $this->assertEquals("2025-04-01T12:00:00.000Z", $result->created_at);
        $this->assertEquals("2025-04-01T12:00:00.000Z", $result->updated_at);
    }

    public function testRetrieveTask()
    {
        $stream = Psr7\stream_for(TaskTest::TASK_JSON);
        list($cmClient, $mockClient) = $this->getMockClient(0, [200], $stream);

        $uuid = "00000000-0000-0000-0000-000000000000";

        $result = Task::retrieve($uuid, $cmClient);
        $request = $mockClient->getRequests()[0];

        $this->assertEquals("GET", $request->getMethod());
        $uri = $request->getUri();
        $this->assertEquals("/v1/tasks/".$uuid, $uri->getPath());

        $this->assertTrue($result instanceof Task);
        $this->assertEquals("00000000-0000-0000-0000-000000000000", $result->uuid);
        $this->assertEquals("cus_00000000-0000-0000-0000-000000000000", $result->customer_uuid);
        $this->assertEquals("This is some task details text.", $result->task_details);
        $this->assertEquals("customer@example.com", $result->assignee);
        $this->assertEquals("2025-04-30T00:00:00Z", $result->due_date);
        $this->assertEquals("2025-04-20T00:00:00Z", $result->completed_at);
        $this->assertEquals("2025-04-01T12:00:00.000Z", $result->created_at);
        $this->assertEquals("2025-04-01T12:00:00.000Z", $result->updated_at);
    }

    public function testUpdateTask()
    {
        $stream = Psr7\stream_for(TaskTest::UPDATED_TASK_JSON);
        list($cmClient, $mockClient) = $this->getMockClient(0, [200], $stream);

        $uuid = "00000000-0000-0000-0000-000000000000";

        $result = Task::update(
            [
            "uuid" => $uuid,
            ], [
            "task_details" => "This is some other task details text.",
            ], $cmClient
        );
        $request = $mockClient->getRequests()[0];

        $this->assertEquals("PATCH", $request->getMethod());
        $uri = $request->getUri();
        $this->assertEquals("/v1/tasks/".$uuid, $uri->getPath());

        $this->assertTrue($result instanceof Task);
        $this->assertEquals("00000000-0000-0000-0000-000000000000", $result->uuid);
        $this->assertEquals("cus_00000000-0000-0000-0000-000000000000", $result->customer_uuid);
        $this->assertEquals("This is some other task details text.", $result->task_details);
        $this->assertEquals("customer@example.com", $result->assignee);
        $this->assertEquals("2025-04-30T00:00:00Z", $result->due_date);
        $this->assertEquals("2025-04-20T00:00:00Z", $result->completed_at);
        $this->assertEquals("2025-04-01T12:00:00.000Z", $result->created_at);
        $this->assertEquals("2025-04-01T12:00:00.000Z", $result->updated_at);
    }

    public function testDeleteTask()
    {
        $stream = Psr7\stream_for("{}");
        list($cmClient, $mockClient) = $this->getMockClient(0, [204], $stream);

        $uuid = "00000000-0000-0000-0000-000000000000";

        $result = (new Task(["uuid" => $uuid], $cmClient))->destroy();
        $request = $mockClient->getRequests()[0];

        $this->assertEquals("DELETE", $request->getMethod());
        $uri = $request->getUri();
        $this->assertEquals("/v1/tasks/".$uuid, $uri->getPath());

        $this->assertEquals("{}", $result);
    }
}
