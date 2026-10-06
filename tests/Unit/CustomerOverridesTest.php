<?php
namespace ChartMogul\Tests;

use ChartMogul\Customer;
use GuzzleHttp\Psr7;

class CustomerOverridesTest extends TestCase
{
    const CUSTOMER_WITH_OVERRIDES_JSON = '{
        "id": 74596,
        "uuid": "cus_00000000-0000-0000-0000-000000000000",
        "external_id": "cus_0001",
        "name": "Adam Smith",
        "company": "Example Company",
        "status": "Active",
        "attributes": {
            "tags": [],
            "custom": { "salesRep": "Gabi" },
            "stripe": {}
        },
        "overrides": {
            "company": true,
            "attributes": { "custom": { "salesRep": true } }
        }
    }';

    const CUSTOMER_WITH_HISTORY_JSON = '{
        "uuid": "cus_00000000-0000-0000-0000-000000000000",
        "company": "Example Company",
        "attributes": {
            "custom": { "salesRep": "Gabi" }
        },
        "overrides": {
            "attributes": { "custom": { "salesRep": true } }
        },
        "historical_values": {
            "company": [
                { "value": "Example Company", "update_performed_at": "2026-09-01T10:00:00Z", "update_performed_by": "sylvia@example.com", "initial": false }
            ],
            "attributes": {
                "custom": {
                    "salesRep": [
                        { "value": "Gabi", "update_performed_at": null, "update_performed_by": null, "initial": true }
                    ]
                }
            }
        }
    }';

    const CUSTOM_ATTRIBUTES_WITH_OVERRIDES_JSON = '{
        "custom": { "channel": "Facebook" },
        "overrides": { "custom": { "channel": true } }
    }';

    const DELETE_ATTRIBUTES_WITH_OVERRIDES_JSON = '{
        "custom": { "channel": "Facebook" },
        "overrides": {},
        "message": "Custom attributes deleted from customer"
    }';

    const DELETE_LAST_ATTRIBUTE_JSON = '{
        "overrides": {},
        "message": "Custom attributes deleted from customer"
    }';

    const ATTRIBUTES_WITH_OVERRIDES_JSON = '{
        "tags": ["vip"],
        "custom": { "salesRep": "Gabi" },
        "overrides": { "custom": { "salesRep": true } },
        "historical_values": {
            "custom": {
                "salesRep": [
                    { "value": "Gabi", "update_performed_at": null, "update_performed_by": null, "initial": true }
                ]
            }
        }
    }';

    const BY_EMAIL_WITH_OVERRIDES_JSON = '{
        "entries": [
            {
                "uuid": "cus_00000000-0000-0000-0000-000000000000",
                "email": "adam@example.com",
                "attributes": { "custom": { "channel": "Facebook" } },
                "overrides": { "attributes": { "custom": { "channel": true } } }
            }
        ]
    }';

    public function testCreateCustomerWithOverrides()
    {
        $stream = Psr7\Utils::streamFor(self::CUSTOMER_WITH_OVERRIDES_JSON);
        list($cmClient, $mockClient) = $this->getMockClient(0, [201], $stream);

        $overrides = [
            'company' => true,
            'attributes' => ['custom' => ['salesRep' => true]]
        ];
        $result = Customer::create(
            [
            'data_source_uuid' => 'ds_00000000-0000-0000-0000-000000000000',
            'external_id' => 'cus_0001',
            'company' => 'Example Company',
            'overrides' => $overrides
            ], $cmClient
        );
        $request = $mockClient->getRequests()[0];

        $this->assertEquals('POST', $request->getMethod());
        $requestBody = json_decode((string) $request->getBody(), true);
        $this->assertEquals($overrides, $requestBody['overrides']);

        $this->assertEquals($overrides, $result->overrides);
    }

    public function testUpdateCustomerWithOverrides()
    {
        $stream = Psr7\Utils::streamFor(self::CUSTOMER_WITH_OVERRIDES_JSON);
        list($cmClient, $mockClient) = $this->getMockClient(0, [200], $stream);

        $uuid = 'cus_00000000-0000-0000-0000-000000000000';
        $result = Customer::update(
            ['customer_uuid' => $uuid],
            ['company' => 'Example Company', 'overrides' => ['company' => true]],
            $cmClient
        );
        $request = $mockClient->getRequests()[0];

        $this->assertEquals('PATCH', $request->getMethod());
        $this->assertEquals('/v1/customers/'.$uuid, $request->getUri()->getPath());
        $requestBody = json_decode((string) $request->getBody(), true);
        $this->assertEquals(['company' => true], $requestBody['overrides']);

        $this->assertEquals(true, $result->overrides['company']);
    }

    public function testRetrieveCustomerWithOverridesAndHistoricalValues()
    {
        $stream = Psr7\Utils::streamFor(self::CUSTOMER_WITH_HISTORY_JSON);
        list($cmClient, $mockClient) = $this->getMockClient(0, [200], $stream);

        $uuid = 'cus_00000000-0000-0000-0000-000000000000';
        $result = Customer::retrieve($uuid, $cmClient, [
            'with_overrides' => 'true',
            'attributes_with_history' => 'company,custom.salesRep'
        ]);
        $request = $mockClient->getRequests()[0];

        $this->assertEquals('GET', $request->getMethod());
        $this->assertEquals(
            'with_overrides=true&attributes_with_history=company%2Ccustom.salesRep',
            $request->getUri()->getQuery()
        );

        $this->assertEquals(['custom' => ['salesRep' => true]], $result->overrides['attributes']);
        $entry = $result->historical_values['company'][0];
        $this->assertEquals('Example Company', $entry['value']);
        $this->assertEquals('2026-09-01T10:00:00Z', $entry['update_performed_at']);
        $this->assertEquals('sylvia@example.com', $entry['update_performed_by']);
        $this->assertFalse($entry['initial']);
        $this->assertArrayHasKey('salesRep', $result->historical_values['attributes']['custom']);
    }

    public function testRetrieveAttributesWithQuery()
    {
        $stream = Psr7\Utils::streamFor(self::ATTRIBUTES_WITH_OVERRIDES_JSON);
        list($cmClient, $mockClient) = $this->getMockClient(0, [200], $stream);

        $customer = new Customer(['uuid' => 'cus_test'], $cmClient);
        $result = $customer->retrieveAttributes(['with_overrides' => 'true']);
        $request = $mockClient->getRequests()[0];

        $this->assertEquals('GET', $request->getMethod());
        $this->assertEquals('/v1/customers/cus_test/attributes', $request->getUri()->getPath());
        $this->assertEquals('with_overrides=true', $request->getUri()->getQuery());

        $this->assertEquals(['custom' => ['salesRep' => true]], $result['overrides']);

        $expectedResponse = json_decode(self::ATTRIBUTES_WITH_OVERRIDES_JSON, true);
        $this->assertEquals($expectedResponse, $result);
        $this->assertEquals(
            ['tags' => ['vip'], 'custom' => ['salesRep' => 'Gabi']],
            $customer->attributes
        );
        $this->assertEquals(
            ['attributes' => ['custom' => ['salesRep' => true]]],
            $customer->overrides
        );
        $this->assertEquals(
            ['attributes' => $expectedResponse['historical_values']],
            $customer->historical_values
        );
    }

    public function testRetrieveAttributesWithoutQueryLeavesOverridesAlone()
    {
        $stream = Psr7\Utils::streamFor('{"tags": ["vip"], "custom": { "salesRep": "Gabi" }}');
        list($cmClient, $mockClient) = $this->getMockClient(0, [200], $stream);

        $customer = new Customer(
            [
            'uuid' => 'cus_test',
            'overrides' => ['company' => true]
            ], $cmClient
        );
        $customer->retrieveAttributes();

        $this->assertEquals(['tags' => ['vip'], 'custom' => ['salesRep' => 'Gabi']], $customer->attributes);
        $this->assertEquals(['company' => true], $customer->overrides);
    }

    public function testAddCustomAttributesWithOverrides()
    {
        $stream = Psr7\Utils::streamFor(self::CUSTOM_ATTRIBUTES_WITH_OVERRIDES_JSON);
        list($cmClient, $mockClient) = $this->getMockClient(0, [200], $stream);

        $customer = new Customer(['uuid' => 'cus_test'], $cmClient);
        $custom = [['type' => 'String', 'key' => 'channel', 'value' => 'Facebook']];
        $overrides = ['custom' => ['channel' => true]];

        $result = $customer->addCustomAttributesWithOverrides($custom, $overrides);
        $request = $mockClient->getRequests()[0];

        $this->assertEquals('POST', $request->getMethod());
        $this->assertEquals('/v1/customers/cus_test/attributes/custom', $request->getUri()->getPath());
        $this->assertEquals(
            ['custom' => $custom, 'overrides' => $overrides],
            json_decode((string) $request->getBody(), true)
        );

        $this->assertEquals($overrides, $result['overrides']);
        $this->assertEquals(['channel' => 'Facebook'], $customer->customAttributes());
    }

    public function testAddCustomAttributesWithOverridesOmitsEmptyOverrides()
    {
        $stream = Psr7\Utils::streamFor(self::CUSTOM_ATTRIBUTES_WITH_OVERRIDES_JSON);
        list($cmClient, $mockClient) = $this->getMockClient(0, [200], $stream);

        $customer = new Customer(['uuid' => 'cus_test'], $cmClient);
        $custom = [['type' => 'String', 'key' => 'channel', 'value' => 'Facebook']];

        $customer->addCustomAttributesWithOverrides($custom);
        $request = $mockClient->getRequests()[0];

        $this->assertEquals(['custom' => $custom], json_decode((string) $request->getBody(), true));
    }

    public function testUpdateCustomAttributesWithOverrides()
    {
        $stream = Psr7\Utils::streamFor(self::CUSTOM_ATTRIBUTES_WITH_OVERRIDES_JSON);
        list($cmClient, $mockClient) = $this->getMockClient(0, [200], $stream);

        $customer = new Customer(['uuid' => 'cus_test'], $cmClient);
        $custom = ['channel' => 'Facebook'];
        $overrides = ['custom' => ['channel' => true]];

        $result = $customer->updateCustomAttributesWithOverrides($custom, $overrides);
        $request = $mockClient->getRequests()[0];

        $this->assertEquals('PUT', $request->getMethod());
        $this->assertEquals(
            ['custom' => $custom, 'overrides' => $overrides],
            json_decode((string) $request->getBody(), true)
        );

        $this->assertEquals($overrides, $result['overrides']);
    }

    public function testRemoveCustomAttributesWithOverrides()
    {
        $stream = Psr7\Utils::streamFor(self::DELETE_ATTRIBUTES_WITH_OVERRIDES_JSON);
        list($cmClient, $mockClient) = $this->getMockClient(0, [202], $stream);

        $customer = new Customer(['uuid' => 'cus_test'], $cmClient);
        $custom = ['age'];
        $overrides = ['custom' => ['age' => false]];

        $result = $customer->removeCustomAttributesWithOverrides($custom, $overrides);
        $request = $mockClient->getRequests()[0];

        $this->assertEquals('DELETE', $request->getMethod());
        $this->assertEquals(
            ['custom' => $custom, 'overrides' => $overrides],
            json_decode((string) $request->getBody(), true)
        );

        $this->assertEquals([], $result['overrides']);
        $this->assertEquals('Custom attributes deleted from customer', $result['message']);
    }

    public function testWriteSyncsOverridesAttributesFromResponse()
    {
        $stream = Psr7\Utils::streamFor(self::CUSTOM_ATTRIBUTES_WITH_OVERRIDES_JSON);
        list($cmClient, $mockClient) = $this->getMockClient(0, [200], $stream);

        $customer = new Customer(
            [
            'uuid' => 'cus_test',
            'overrides' => [
                'company' => true,
                'attributes' => ['custom' => ['salesRep' => true]]
            ]
            ], $cmClient
        );

        $customer->updateCustomAttributesWithOverrides(
            ['channel' => 'Facebook'],
            ['custom' => ['channel' => true]]
        );

        $this->assertEquals(
            [
                'company' => true,
                'attributes' => ['custom' => ['channel' => true]]
            ],
            $customer->overrides
        );
    }

    public function testRemoveClearsOverridesAttributesWhenNoneLeft()
    {
        $stream = Psr7\Utils::streamFor(self::DELETE_ATTRIBUTES_WITH_OVERRIDES_JSON);
        list($cmClient, $mockClient) = $this->getMockClient(0, [202], $stream);

        $customer = new Customer(
            [
            'uuid' => 'cus_test',
            'overrides' => [
                'company' => true,
                'attributes' => ['custom' => ['age' => true]]
            ]
            ], $cmClient
        );

        $customer->removeCustomAttributesWithOverrides(['age'], ['custom' => ['age' => false]]);

        $this->assertEquals(['company' => true], $customer->overrides);
    }

    public function testRemoveLastAttributeOmitsCustomKey()
    {
        $stream = Psr7\Utils::streamFor(self::DELETE_LAST_ATTRIBUTE_JSON);
        list($cmClient, $mockClient) = $this->getMockClient(0, [202], $stream);

        $customer = new Customer(['uuid' => 'cus_test'], $cmClient);

        $result = $customer->removeCustomAttributesWithOverrides(['age']);

        $this->assertEquals([], $customer->customAttributes());
        $this->assertEquals('Custom attributes deleted from customer', $result['message']);
    }

    public function testUpdateCustomAttributesKeepsAttributesNamedCustomAndOverrides()
    {
        $stream = Psr7\Utils::streamFor(self::CUSTOM_ATTRIBUTES_WITH_OVERRIDES_JSON);
        list($cmClient, $mockClient) = $this->getMockClient(0, [200], $stream);

        $customer = new Customer(['uuid' => 'cus_test'], $cmClient);

        $result = $customer->updateCustomAttributes(['custom' => 'A', 'overrides' => 'B']);
        $request = $mockClient->getRequests()[0];

        $this->assertEquals('PUT', $request->getMethod());
        $this->assertEquals(
            ['custom' => ['custom' => 'A', 'overrides' => 'B']],
            json_decode((string) $request->getBody(), true)
        );

        $this->assertEquals(['channel' => 'Facebook'], $result);
    }

    public function testAddCustomAttributesKeepsEnvelopeShapedArrayInput()
    {
        $stream = Psr7\Utils::streamFor(self::CUSTOM_ATTRIBUTES_WITH_OVERRIDES_JSON);
        list($cmClient, $mockClient) = $this->getMockClient(0, [200], $stream);

        $customer = new Customer(['uuid' => 'cus_test'], $cmClient);
        $input = [
            'custom' => [['type' => 'String', 'key' => 'channel', 'value' => 'Facebook']],
            'overrides' => ['custom' => ['channel' => true]]
        ];

        $result = $customer->addCustomAttributes($input);
        $request = $mockClient->getRequests()[0];

        $this->assertEquals('POST', $request->getMethod());
        $this->assertEquals(['custom' => $input], json_decode((string) $request->getBody(), true));

        $this->assertEquals(['channel' => 'Facebook'], $result);
    }

    public function testRemoveCustomAttributesKeepsEnvelopeShapedArrayInput()
    {
        $stream = Psr7\Utils::streamFor(self::DELETE_ATTRIBUTES_WITH_OVERRIDES_JSON);
        list($cmClient, $mockClient) = $this->getMockClient(0, [202], $stream);

        $customer = new Customer(['uuid' => 'cus_test'], $cmClient);
        $input = [
            'custom' => ['age'],
            'overrides' => ['custom' => ['age' => false]]
        ];

        $result = $customer->removeCustomAttributes($input);
        $request = $mockClient->getRequests()[0];

        $this->assertEquals('DELETE', $request->getMethod());
        $this->assertEquals(['custom' => $input], json_decode((string) $request->getBody(), true));

        $this->assertEquals(['channel' => 'Facebook'], $result);
    }

    public function testAddCustomAttributesByEmailWithOverrides()
    {
        $stream = Psr7\Utils::streamFor(self::BY_EMAIL_WITH_OVERRIDES_JSON);
        list($cmClient, $mockClient) = $this->getMockClient(0, [200], $stream);

        $custom = [['type' => 'String', 'key' => 'channel', 'value' => 'Facebook']];
        $overrides = ['custom' => ['channel' => true]];

        $result = Customer::addCustomAttributesByEmail('adam@example.com', $custom, $cmClient, $overrides);
        $request = $mockClient->getRequests()[0];

        $this->assertEquals('POST', $request->getMethod());
        $this->assertEquals('/v1/customers/attributes/custom', $request->getUri()->getPath());
        $requestBody = json_decode((string) $request->getBody(), true);
        $this->assertEquals($overrides, $requestBody['overrides']);

        $this->assertEquals(
            ['attributes' => ['custom' => ['channel' => true]]],
            $result['entries'][0]['overrides']
        );
    }

    public function testAddCustomAttributesByEmailWithoutOverridesOmitsKey()
    {
        $stream = Psr7\Utils::streamFor(self::BY_EMAIL_WITH_OVERRIDES_JSON);
        list($cmClient, $mockClient) = $this->getMockClient(0, [200], $stream);

        $custom = [['type' => 'String', 'key' => 'channel', 'value' => 'Facebook']];

        Customer::addCustomAttributesByEmail('adam@example.com', $custom, $cmClient);
        $request = $mockClient->getRequests()[0];

        $requestBody = json_decode((string) $request->getBody(), true);
        $this->assertArrayNotHasKey('overrides', $requestBody);
    }
}
