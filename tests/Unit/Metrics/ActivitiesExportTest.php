<?php
namespace ChartMogul\Tests;

use ChartMogul\Http\Client;
use ChartMogul\Metrics;
use ChartMogul\Metrics\ActivitiesExport;
use ChartMogul\Exceptions\ChartMogulException;
use GuzzleHttp\Psr7;
use GuzzleHttp\Psr7\Response;

class ActivitiesExportTest extends TestCase
{

    const POST_JSON  = '{
							      "id": "7f554dba-4a41-4cb2-9790-2045e4c3a5b1",
							      "status": "pending",
							      "file_url": null,
							      "params": {
							        "kind": "activities",
							        "params": {
							          "activity_type": "contraction",
							          "start_date": "2020-01-01",
							          "end_date": "2020-12-31"
							        }
							      },
							      "expires_at": null,
							      "created_at": "2021-07-12T14:46:56+00:00"
							    }';

    const GET_JSON = '{
							      "id": "7f554dba-4a41-4cb2-9790-2045e4c3a5b1",
							      "status": "succeeded",
							      "file_url": "https://chartmogul-customer-export.s3.eu-west-1.amazonaws.com/activities-acme-corp-91e1ca88-d747-4e25-83d9-2b752033bdba.zip",
							      "params": {
							        "kind": "activities",
							        "params": {
							          "activity_type": "contraction",
							          "start_date": "2020-01-01",
							          "end_date": "2020-12-31"
							        }
							      },
							      "expires_at": "2021-07-19T14:46:58+00:00",
							      "created_at": "2021-07-12T14:46:56+00:00"
							    }';


    public function testActivitiesExportCreation()
    {
        $stream = Psr7\stream_for(ActivitiesExportTest::POST_JSON);
        list($cmClient, $mockClient) = $this->getMockClient(0, [200], $stream);

        $result = ActivitiesExport::create(['type' => 'contraction', 'start_date' => '2020-01-01', 'end_date' => '2020-12-31'], $cmClient);
        $request = $mockClient->getRequests()[0];

        $this->assertEquals("POST", $request->getMethod());
        $uri = $request->getUri();
        $this->assertEquals("/v1/activities_export", $uri->getPath());

        $this->assertTrue($result instanceof ActivitiesExport);
        $this->assertEquals($result->id, "7f554dba-4a41-4cb2-9790-2045e4c3a5b1");
        $this->assertEquals($result->status, "pending");
        $this->assertEquals($result->file_url, null);

    }

    public function testActivitiesExportRetrieval()
    {
        $stream = Psr7\stream_for(ActivitiesExportTest::GET_JSON);
        list($cmClient, $mockClient) = $this->getMockClient(0, [200], $stream);

        $id = '7f554dba-4a41-4cb2-9790-2045e4c3a5b1';

        $result = ActivitiesExport::retrieve($id, $cmClient);
        $request = $mockClient->getRequests()[0];

        $this->assertEquals("GET", $request->getMethod());
        $uri = $request->getUri();
        $this->assertEquals("/v1/activities_export/7f554dba-4a41-4cb2-9790-2045e4c3a5b1", $uri->getPath());

        $this->assertTrue($result instanceof ActivitiesExport);
        $this->assertEquals($result->id, "7f554dba-4a41-4cb2-9790-2045e4c3a5b1");
        $this->assertEquals($result->status, "succeeded");
        $this->assertEquals($result->file_url, "https://chartmogul-customer-export.s3.eu-west-1.amazonaws.com/activities-acme-corp-91e1ca88-d747-4e25-83d9-2b752033bdba.zip");

    }
}
