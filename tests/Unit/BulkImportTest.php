<?php
namespace ChartMogul\Tests;

use ChartMogul\BulkImport;
use GuzzleHttp\Psr7;

class BulkImportTest extends TestCase
{
    const BULK_IMPORT_JSON = '{
        "id": "ds_import_1",
        "data_source_uuid": "ds_1fm3eaac-62d0-31ec-clf4-4bf0mbe81aba",
        "status": "queued",
        "created_at": "2024-01-01T00:00:00.000Z",
        "updated_at": "2024-01-01T00:00:00.000Z"
    }';

    public function testCreate()
    {
        $stream = Psr7\stream_for(self::BULK_IMPORT_JSON);
        list($cmClient, $mockClient) = $this->getMockClient(0, [200], $stream);

        $dsUuid = 'ds_1fm3eaac-62d0-31ec-clf4-4bf0mbe81aba';
        $result = BulkImport::create($dsUuid, ['customers' => []], $cmClient);

        $request = $mockClient->getRequests()[0];
        $this->assertEquals('POST', $request->getMethod());
        $uri = $request->getUri();
        $this->assertEquals('/v1/data_sources/' . $dsUuid . '/json_imports', $uri->getPath());
        $this->assertTrue($result instanceof BulkImport);
    }

    public function testRetrieve()
    {
        $stream = Psr7\stream_for(self::BULK_IMPORT_JSON);
        list($cmClient, $mockClient) = $this->getMockClient(0, [200], $stream);

        $dsUuid = 'ds_1fm3eaac-62d0-31ec-clf4-4bf0mbe81aba';
        $importId = 'ds_import_1';
        $result = BulkImport::retrieve($dsUuid, $importId, $cmClient);

        $request = $mockClient->getRequests()[0];
        $this->assertEquals('GET', $request->getMethod());
        $uri = $request->getUri();
        $this->assertEquals('/v1/data_sources/' . $dsUuid . '/json_imports/' . $importId, $uri->getPath());
        $this->assertTrue($result instanceof BulkImport);
    }
}
