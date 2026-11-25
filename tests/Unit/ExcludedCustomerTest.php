<?php
namespace ChartMogul\Tests;

use ChartMogul\ExcludedCustomer;
use ChartMogul\ReincludedCustomer;
use ChartMogul\Exceptions\NotFoundException;
use GuzzleHttp\Psr7;

class ExcludedCustomerTest extends TestCase
{
    const LIST_EXCLUDED_CUSTOMERS_JSON = '{
      "entries": [
        {
          "customer_uuid": "cus_00000000-0000-0000-0000-000000000000",
          "data_source_uuid": "ds_00000000-0000-0000-0000-000000000000",
          "external_id": "ext-1",
          "name": "Acme",
          "email": "acme@example.com",
          "excluded_at": "2026-11-20T10:00:00.000Z"
        },
        {
          "customer_uuid": "cus_00000000-0000-0000-0000-000000000000",
          "data_source_uuid": "ds_00000000-0000-0000-0000-000000000001",
          "external_id": "ext-2",
          "name": "Acme",
          "email": "acme@example.com",
          "excluded_at": "2026-11-19T10:00:00.000Z"
        }
      ],
      "cursor": "cursor==",
      "has_more": true
    }';

    const REINCLUDE_JSON = '{
      "customer_uuid": "cus_00000000-0000-0000-0000-000000000000",
      "status": "reimporting"
    }';

    public function testListExcludedCustomers()
    {
        $stream = Psr7\Utils::streamFor(ExcludedCustomerTest::LIST_EXCLUDED_CUSTOMERS_JSON);
        list($cmClient, $mockClient) = $this->getMockClient(0, [200], $stream);

        $result = ExcludedCustomer::all(["cursor" => "cursor==", "per_page" => 2], $cmClient);
        $request = $mockClient->getRequests()[0];

        $this->assertEquals("GET", $request->getMethod());
        $uri = $request->getUri();
        $this->assertEquals("/v1/excluded_customers", $uri->getPath());
        parse_str($uri->getQuery(), $query);
        $this->assertEquals(["cursor" => "cursor==", "per_page" => "2"], $query);

        $this->assertCount(2, $result);
        $this->assertTrue($result[0] instanceof ExcludedCustomer);
        $this->assertEquals("cus_00000000-0000-0000-0000-000000000000", $result[0]->customer_uuid);
        $this->assertEquals("ds_00000000-0000-0000-0000-000000000000", $result[0]->data_source_uuid);
        $this->assertEquals("ext-1", $result[0]->external_id);
        $this->assertEquals("Acme", $result[0]->name);
        $this->assertEquals("acme@example.com", $result[0]->email);
        $this->assertEquals("2026-11-20T10:00:00.000Z", $result[0]->excluded_at);
        $this->assertEquals("ds_00000000-0000-0000-0000-000000000001", $result[1]->data_source_uuid);
        $this->assertEquals("cursor==", $result->cursor);
        $this->assertEquals(true, $result->has_more);
    }

    public function testReinclude()
    {
        $stream = Psr7\Utils::streamFor(ExcludedCustomerTest::REINCLUDE_JSON);
        list($cmClient, $mockClient) = $this->getMockClient(0, [202], $stream);

        $customer_uuid = "cus_00000000-0000-0000-0000-000000000000";

        $result = ExcludedCustomer::reinclude($customer_uuid, $cmClient);
        $request = $mockClient->getRequests()[0];

        $this->assertEquals("DELETE", $request->getMethod());
        $this->assertEquals("/v1/excluded_customers/".$customer_uuid, $request->getUri()->getPath());

        $this->assertTrue($result instanceof ReincludedCustomer);
        $this->assertEquals($customer_uuid, $result->customer_uuid);
        $this->assertEquals("reimporting", $result->status);
    }

    public function testReincludeCustomerThatIsNotExcluded()
    {
        $stream = Psr7\Utils::streamFor('{"code": 404, "message": "Excluded customer \\"cus_00000000-0000-0000-0000-000000000000\\" does not exist", "param": "customer_uuid"}');
        list($cmClient, $mockClient) = $this->getMockClient(0, [404], $stream);

        $customer_uuid = "cus_00000000-0000-0000-0000-000000000000";

        try {
            ExcludedCustomer::reinclude($customer_uuid, $cmClient);
            $this->fail("Expected NotFoundException");
        } catch (NotFoundException $e) {
            $this->assertEquals(404, $e->getStatusCode());
        }
        $request = $mockClient->getRequests()[0];
        $this->assertEquals("DELETE", $request->getMethod());
        $this->assertEquals("/v1/excluded_customers/".$customer_uuid, $request->getUri()->getPath());
    }
}
