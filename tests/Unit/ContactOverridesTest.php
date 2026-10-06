<?php
namespace ChartMogul\Tests;

use ChartMogul\Contact;
use GuzzleHttp\Psr7;

class ContactOverridesTest extends TestCase
{
    const CONTACT_WITH_OVERRIDES_JSON = '{
      "uuid": "con_00000000-0000-0000-0000-000000000000",
      "customer_uuid": "cus_00000000-0000-0000-0000-000000000000",
      "data_source_uuid": "ds_00000000-0000-0000-0000-000000000000",
      "first_name": "Adam",
      "title": "CEO",
      "custom": {},
      "overrides": { "title": true }
    }';

    const CONTACT_WITH_HISTORY_JSON = '{
      "uuid": "con_00000000-0000-0000-0000-000000000000",
      "title": "CEO",
      "custom": {},
      "overrides": { "title": true },
      "historical_values": {
        "title": [
          { "value": "CEO", "update_performed_at": "2026-09-01T10:00:00Z", "update_performed_by": "sylvia@example.com", "initial": false },
          { "value": "CTO", "update_performed_at": null, "update_performed_by": null, "initial": true }
        ]
      }
    }';

    public function testCreateContactWithOverrides()
    {
        $stream = Psr7\Utils::streamFor(self::CONTACT_WITH_OVERRIDES_JSON);
        list($cmClient, $mockClient) = $this->getMockClient(0, [201], $stream);

        $result = Contact::create(
            [
            'customer_uuid' => 'cus_00000000-0000-0000-0000-000000000000',
            'data_source_uuid' => 'ds_00000000-0000-0000-0000-000000000000',
            'title' => 'CEO',
            'overrides' => ['title' => true]
            ], $cmClient
        );
        $request = $mockClient->getRequests()[0];

        $this->assertEquals('POST', $request->getMethod());
        $requestBody = json_decode((string) $request->getBody(), true);
        $this->assertEquals(['title' => true], $requestBody['overrides']);

        $this->assertEquals(['title' => true], $result->overrides);
    }

    public function testUpdateContactWithOverrides()
    {
        $stream = Psr7\Utils::streamFor(self::CONTACT_WITH_OVERRIDES_JSON);
        list($cmClient, $mockClient) = $this->getMockClient(0, [200], $stream);

        $uuid = 'con_00000000-0000-0000-0000-000000000000';
        $result = Contact::update(
            ['contact_uuid' => $uuid],
            ['title' => 'CEO', 'overrides' => ['title' => true]],
            $cmClient
        );
        $request = $mockClient->getRequests()[0];

        $this->assertEquals('PATCH', $request->getMethod());
        $this->assertEquals('/v1/contacts/'.$uuid, $request->getUri()->getPath());
        $requestBody = json_decode((string) $request->getBody(), true);
        $this->assertEquals(['title' => true], $requestBody['overrides']);

        $this->assertEquals(['title' => true], $result->overrides);
    }

    public function testRetrieveContactWithOverridesAndHistoricalValues()
    {
        $stream = Psr7\Utils::streamFor(self::CONTACT_WITH_HISTORY_JSON);
        list($cmClient, $mockClient) = $this->getMockClient(0, [200], $stream);

        $uuid = 'con_00000000-0000-0000-0000-000000000000';
        $result = Contact::retrieve($uuid, $cmClient, [
            'with_overrides' => 'true',
            'attributes_with_history' => 'title'
        ]);
        $request = $mockClient->getRequests()[0];

        $this->assertEquals('GET', $request->getMethod());
        $this->assertEquals(
            'with_overrides=true&attributes_with_history=title',
            $request->getUri()->getQuery()
        );

        $this->assertEquals(['title' => true], $result->overrides);
        $entries = $result->historical_values['title'];
        $this->assertCount(2, $entries);
        $this->assertEquals('CEO', $entries[0]['value']);
        $this->assertTrue($entries[1]['initial']);
    }
}
