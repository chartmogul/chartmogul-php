<?php
namespace ChartMogul\Tests;

use ChartMogul\Http\Client;
use ChartMogul\DataSource;
use ChartMogul\Resource\Collection;
use ChartMogul\Exceptions\ChartMogulException;
use GuzzleHttp\Psr7;
use GuzzleHttp\Psr7\Response;

class DataSourceTest extends TestCase
{
    const CREATE_DATA_SOURCE_JSON = '{
        "uuid": "ds_fef05d54-47b4-431b-aed2-eb6b9e545430",
        "name": "In-house Billing",
        "created_at": "2016-01-10T15:34:05.000Z",
        "status": "idle",
        "system": "custom"
    }';

    const RETRIEVE_DATA_SOURCE_JSON = '{
        "uuid": "ds_fef05d54-47b4-431b-aed2-eb6b9e545430",
        "name": "In-house Billing",
        "created_at": "2016-01-10T15:34:05.000Z",
        "status": "idle",
        "system": "custom",
        "processing_status": {
            "processed": 61,
            "failed": 3,
            "pending": 0
        },
        "auto_churn_subscription_setting": true,
        "invoice_handling_setting": {
            "manual": {
                "create_subscription_when_invoice_is": "open",
                "update_subscription_when_invoice_is": "open",
                "prevent_subscription_for_invoice_voided": true,
                "prevent_subscription_for_invoice_refunded": false,
                "prevent_subscription_for_invoice_written_off": true
            },
            "automatic": {
                "create_subscription_when_invoice_is": "open",
                "update_subscription_when_invoice_is": "open",
                "prevent_subscription_for_invoice_voided": true,
                "prevent_subscription_for_invoice_refunded": false,
                "prevent_subscription_for_invoice_written_off": true
            }
        }
    }';

    const LIST_DATA_SOURCES_JSON = '{
        "data_sources": [
            {
                "uuid": "ds_fef05d54-47b4-431b-aed2-eb6b9e545430",
                "name": "In-house Billing",
                "created_at": "2016-01-10T15:34:05.000Z",
                "status": "idle",
                "system": "custom",
                "processing_status": {
                    "processed": 61,
                    "failed": 3,
                    "pending": 0
                },
                "auto_churn_subscription_setting": true,
                "invoice_handling_setting": {
                    "manual": {
                        "create_subscription_when_invoice_is": "open",
                        "update_subscription_when_invoice_is": "open",
                        "prevent_subscription_for_invoice_voided": true,
                        "prevent_subscription_for_invoice_refunded": false,
                        "prevent_subscription_for_invoice_written_off": true
                    },
                    "automatic": {
                        "create_subscription_when_invoice_is": "open",
                        "update_subscription_when_invoice_is": "open",
                        "prevent_subscription_for_invoice_voided": true,
                        "prevent_subscription_for_invoice_refunded": false,
                        "prevent_subscription_for_invoice_written_off": true
                    }
                }
            },
            {
                "uuid": "ds_1234abcd-47b4-431b-aed2-eb6b9e545431",
                "name": "Stripe",
                "created_at": "2016-01-11T10:15:20.000Z",
                "status": "imported",
                "system": "stripe",
                "processing_status": {
                    "processed": 61,
                    "failed": 3,
                    "pending": 0
                },
                "auto_churn_subscription_setting": "disabled",
                "invoice_handling_setting": "multiple_invoices_per_subscription"
            }
        ],
        "has_more": false,
        "per_page": 200,
        "page": 1
    }';

    public function testCreateDataSource()
    {
        $stream = Psr7\stream_for(DataSourceTest::CREATE_DATA_SOURCE_JSON);
        list($cmClient, $mockClient) = $this->getMockClient(0, [200], $stream);

        $result = DataSource::create([
            'name' => 'In-house Billing'
        ], $cmClient);

        $request = $mockClient->getRequests()[0];

        $this->assertEquals("POST", $request->getMethod());
        $uri = $request->getUri();
        $this->assertEquals("", $uri->getQuery());
        $this->assertEquals("/v1/data_sources", $uri->getPath());

        $this->assertTrue($result instanceof DataSource);
        $this->assertEquals("ds_fef05d54-47b4-431b-aed2-eb6b9e545430", $result->uuid);
        $this->assertEquals("In-house Billing", $result->name);
        $this->assertEquals("idle", $result->status);
        $this->assertEquals("custom", $result->system);
        $this->assertEquals("2016-01-10T15:34:05.000Z", $result->created_at);
    }

    public function testRetrieveDataSource()
    {
        $stream = Psr7\stream_for(DataSourceTest::RETRIEVE_DATA_SOURCE_JSON);
        list($cmClient, $mockClient) = $this->getMockClient(0, [200], $stream);

        $uuid = 'ds_fef05d54-47b4-431b-aed2-eb6b9e545430';

        $result = DataSource::retrieve($uuid, [
          'with_processing_status' => true,
          'with_auto_churn_subscription_setting' => true,
          'with_invoice_handling_setting' => true
        ], $cmClient);
        $request = $mockClient->getRequests()[0];

        $this->assertEquals("GET", $request->getMethod());
        $uri = $request->getUri();
        $this->assertEquals("with_processing_status=1&with_auto_churn_subscription_setting=1&with_invoice_handling_setting=1", $uri->getQuery());
        $this->assertEquals("/v1/data_sources/".$uuid, $uri->getPath());

        $this->assertTrue($result instanceof DataSource);
        $this->assertEquals($uuid, $result->uuid);
        $this->assertEquals("In-house Billing", $result->name);
        $this->assertEquals("idle", $result->status);
        $this->assertEquals("custom", $result->system);
        $this->assertEquals("2016-01-10T15:34:05.000Z", $result->created_at);
        $this->assertEquals(["processed" => 61, "failed" => 3, "pending" => 0], $result->processing_status);
        $this->assertEquals(true, $result->auto_churn_subscription_setting);
        $this->assertIsArray($result->invoice_handling_setting);
        $this->assertArrayHasKey('manual', $result->invoice_handling_setting);
        $this->assertArrayHasKey('automatic', $result->invoice_handling_setting);
    }

    public function testGetDataSource()
    {
        $stream = Psr7\stream_for(DataSourceTest::RETRIEVE_DATA_SOURCE_JSON);
        list($cmClient, $mockClient) = $this->getMockClient(0, [200], $stream);

        $uuid = 'ds_fef05d54-47b4-431b-aed2-eb6b9e545430';

        // Test the alias method get()
        $result = DataSource::get($uuid, [], $cmClient);
        $request = $mockClient->getRequests()[0];

        $this->assertEquals("GET", $request->getMethod());
        $uri = $request->getUri();
        $this->assertEquals("", $uri->getQuery());
        $this->assertEquals("/v1/data_sources/".$uuid, $uri->getPath());

        $this->assertTrue($result instanceof DataSource);
        $this->assertEquals($uuid, $result->uuid);
    }

    public function testListDataSources()
    {
        $stream = Psr7\stream_for(DataSourceTest::LIST_DATA_SOURCES_JSON);
        list($cmClient, $mockClient) = $this->getMockClient(0, [200], $stream);

        $result = DataSource::all([
          'with_processing_status' => true,
          'with_auto_churn_subscription_setting' => true,
          'with_invoice_handling_setting' => true
        ], $cmClient);
        $request = $mockClient->getRequests()[0];

        $this->assertEquals("GET", $request->getMethod());
        $uri = $request->getUri();
        $this->assertEquals("with_processing_status=1&with_auto_churn_subscription_setting=1&with_invoice_handling_setting=1", $uri->getQuery());
        $this->assertEquals("/v1/data_sources", $uri->getPath());

        $this->assertTrue($result instanceof Collection);
        $this->assertCount(2, $result);
        $this->assertTrue($result[0] instanceof DataSource);
        $this->assertTrue($result[1] instanceof DataSource);

        // Test first data source
        $this->assertEquals("ds_fef05d54-47b4-431b-aed2-eb6b9e545430", $result[0]->uuid);
        $this->assertEquals("In-house Billing", $result[0]->name);
        $this->assertEquals("idle", $result[0]->status);
        $this->assertEquals("custom", $result[0]->system);

        // Test second data source
        $this->assertEquals("ds_1234abcd-47b4-431b-aed2-eb6b9e545431", $result[1]->uuid);
        $this->assertEquals("Stripe", $result[1]->name);
        $this->assertEquals("imported", $result[1]->status);
        $this->assertEquals("stripe", $result[1]->system);

        // Test pagination properties
        $this->assertEquals(false, $result->has_more);
        $this->assertEquals(200, $result->per_page);
        $this->assertEquals(1, $result->page);
    }

    public function testDeleteDataSource()
    {
        $stream = Psr7\stream_for('{}');
        list($cmClient, $mockClient) = $this->getMockClient(0, [204], $stream);

        $uuid = 'ds_fef05d54-47b4-431b-aed2-eb6b9e545430';
        $dataSource = new DataSource(['uuid' => $uuid], $cmClient);

        $result = $dataSource->destroy();
        $request = $mockClient->getRequests()[0];

        $this->assertEquals("DELETE", $request->getMethod());
        $uri = $request->getUri();
        $this->assertEquals("", $uri->getQuery());
        $this->assertEquals("/v1/data_sources/".$uuid, $uri->getPath());

        $this->assertTrue($result);
    }

    public function testDataSourceProperties()
    {
        $dataSource = new DataSource([
            'uuid' => 'ds_test-uuid',
            'name' => 'Test Data Source',
            'status' => 'active',
            'created_at' => '2023-01-01T00:00:00.000Z',
            'system' => 'custom',
            'processing_status' => ['processed' => 61, 'failed' => 3, 'pending' => 0],
            'auto_churn_subscription_setting' => true,
            'invoice_handling_setting' => [
                'manual' => [
                    'create_subscription_when_invoice_is' => 'open',
                    'update_subscription_when_invoice_is' => 'open',
                    'prevent_subscription_for_invoice_voided' => true,
                    'prevent_subscription_for_invoice_refunded' => false,
                    'prevent_subscription_for_invoice_written_off' => true
                ],
                'automatic' => [
                    'create_subscription_when_invoice_is' => 'open',
                    'update_subscription_when_invoice_is' => 'open',
                    'prevent_subscription_for_invoice_voided' => true,
                    'prevent_subscription_for_invoice_refunded' => false,
                    'prevent_subscription_for_invoice_written_off' => true
                ]
            ]
        ]);

        // Test read-only properties
        $this->assertEquals('ds_test-uuid', $dataSource->uuid);
        $this->assertEquals('active', $dataSource->status);
        $this->assertEquals('2023-01-01T00:00:00.000Z', $dataSource->created_at);
        $this->assertEquals('custom', $dataSource->system);
        $this->assertEquals(['processed' => 61, 'failed' => 3, 'pending' => 0], $dataSource->processing_status);
        $this->assertEquals(true, $dataSource->auto_churn_subscription_setting);
        $this->assertIsArray($dataSource->invoice_handling_setting);
        $this->assertArrayHasKey('manual', $dataSource->invoice_handling_setting);
        $this->assertArrayHasKey('automatic', $dataSource->invoice_handling_setting);

        // Test writable property
        $this->assertEquals('Test Data Source', $dataSource->name);

        // Test that name is writable
        $dataSource->name = 'Updated Name';
        $this->assertEquals('Updated Name', $dataSource->name);
    }

    public function testDataSourceConstants()
    {
        $this->assertEquals('/v1/data_sources', DataSource::RESOURCE_PATH);
        $this->assertEquals('Data Source', DataSource::RESOURCE_NAME);
        $this->assertEquals('data_sources', DataSource::ROOT_KEY);
    }

    public function testDataSourceToArray()
    {
        $dataSource = new DataSource([
            'uuid' => 'ds_test-uuid',
            'name' => 'Test Data Source',
            'status' => 'active',
            'created_at' => '2023-01-01T00:00:00.000Z',
            'system' => 'custom'
        ]);

        $array = $dataSource->toArray();

        $this->assertIsArray($array);
        $this->assertEquals('ds_test-uuid', $array['uuid']);
        $this->assertEquals('Test Data Source', $array['name']);
        $this->assertEquals('active', $array['status']);
        $this->assertEquals('2023-01-01T00:00:00.000Z', $array['created_at']);
        $this->assertEquals('custom', $array['system']);
    }
}
