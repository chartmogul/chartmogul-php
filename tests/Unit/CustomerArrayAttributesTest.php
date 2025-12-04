<?php
namespace ChartMogul\Tests;

use ChartMogul\Http\Client;
use ChartMogul\Customer;
use GuzzleHttp\Psr7;
use GuzzleHttp\Psr7\Response;

class CustomerArrayAttributesTest extends TestCase
{
    const CUSTOM_ATTRIBUTES_RESPONSE = '{
        "custom": {
            "channel": "Facebook",
            "age": 25,
            "plan": "Premium"
        }
    }';

    const TAGS_RESPONSE = '{
        "tags": ["vip", "enterprise", "priority"]
    }';

    public function testAddCustomAttributesWithArray()
    {
        $stream = Psr7\stream_for(self::CUSTOM_ATTRIBUTES_RESPONSE);
        list($cmClient, $mockClient) = $this->getMockClient(0, [200], $stream);

        $customer = new Customer(['uuid' => 'cus_test'], $cmClient);

        // Test with array input (new functionality)
        $attributes = [
            ['type' => 'String', 'key' => 'channel', 'value' => 'Facebook'],
            ['type' => 'Integer', 'key' => 'age', 'value' => 25],
            ['type' => 'String', 'key' => 'plan', 'value' => 'Premium']
        ];

        $result = $customer->addCustomAttributes($attributes);
        $request = $mockClient->getRequests()[0];

        $this->assertEquals("POST", $request->getMethod());
        $uri = $request->getUri();
        $this->assertEquals("/v1/customers/cus_test/attributes/custom", $uri->getPath());

        // Verify the request body contains the array
        $requestBody = json_decode((string) $request->getBody(), true);
        $this->assertEquals($attributes, $requestBody['custom']);

        // Verify response handling
        $this->assertEquals(['channel' => 'Facebook', 'age' => 25, 'plan' => 'Premium'], $result);
    }

    public function testAddCustomAttributesWithIndividualArgs()
    {
        $stream = Psr7\stream_for(self::CUSTOM_ATTRIBUTES_RESPONSE);
        list($cmClient, $mockClient) = $this->getMockClient(0, [200], $stream);

        $customer = new Customer(['uuid' => 'cus_test'], $cmClient);

        // Test with individual arguments (existing functionality)
        $attr1 = ['type' => 'String', 'key' => 'channel', 'value' => 'Facebook'];
        $attr2 = ['type' => 'Integer', 'key' => 'age', 'value' => 25];

        $result = $customer->addCustomAttributes($attr1, $attr2);
        $request = $mockClient->getRequests()[0];

        $this->assertEquals("POST", $request->getMethod());

        // Verify the request body contains individual args as array
        $requestBody = json_decode((string) $request->getBody(), true);
        $this->assertEquals([$attr1, $attr2], $requestBody['custom']);
    }

    public function testAddTagsWithArray()
    {
        $stream = Psr7\stream_for(self::TAGS_RESPONSE);
        list($cmClient, $mockClient) = $this->getMockClient(0, [200], $stream);

        $customer = new Customer(['uuid' => 'cus_test'], $cmClient);

        // Test with array input (new functionality)
        $tags = ['vip', 'enterprise', 'priority'];

        $result = $customer->addTags($tags);
        $request = $mockClient->getRequests()[0];

        $this->assertEquals("POST", $request->getMethod());
        $uri = $request->getUri();
        $this->assertEquals("/v1/customers/cus_test/attributes/tags", $uri->getPath());

        // Verify the request body contains the array
        $requestBody = json_decode((string) $request->getBody(), true);
        $this->assertEquals($tags, $requestBody['tags']);

        // Verify response handling
        $this->assertEquals($tags, $result);
    }

    public function testAddTagsWithIndividualArgs()
    {
        $stream = Psr7\stream_for(self::TAGS_RESPONSE);
        list($cmClient, $mockClient) = $this->getMockClient(0, [200], $stream);

        $customer = new Customer(['uuid' => 'cus_test'], $cmClient);

        // Test with individual arguments (existing functionality)
        $result = $customer->addTags('vip', 'enterprise', 'priority');
        $request = $mockClient->getRequests()[0];

        $this->assertEquals("POST", $request->getMethod());

        // Verify the request body contains individual args as array
        $requestBody = json_decode((string) $request->getBody(), true);
        $this->assertEquals(['vip', 'enterprise', 'priority'], $requestBody['tags']);
    }

    public function testRemoveCustomAttributesWithArray()
    {
        $stream = Psr7\stream_for(self::CUSTOM_ATTRIBUTES_RESPONSE);
        list($cmClient, $mockClient) = $this->getMockClient(0, [200], $stream);

        $customer = new Customer(['uuid' => 'cus_test'], $cmClient);

        // Test with array input
        $attributes = [
            ['key' => 'old_field'],
            ['key' => 'unused_field']
        ];

        $result = $customer->removeCustomAttributes($attributes);
        $request = $mockClient->getRequests()[0];

        $this->assertEquals("DELETE", $request->getMethod());
        $uri = $request->getUri();
        $this->assertEquals("/v1/customers/cus_test/attributes/custom", $uri->getPath());

        // Verify the request body contains the array
        $requestBody = json_decode((string) $request->getBody(), true);
        $this->assertEquals($attributes, $requestBody['custom']);
    }

    public function testRemoveTagsWithArray()
    {
        $stream = Psr7\stream_for(self::TAGS_RESPONSE);
        list($cmClient, $mockClient) = $this->getMockClient(0, [200], $stream);

        $customer = new Customer(['uuid' => 'cus_test'], $cmClient);

        // Test with array input
        $tags = ['old_tag', 'unused_tag'];

        $result = $customer->removeTags($tags);
        $request = $mockClient->getRequests()[0];

        $this->assertEquals("DELETE", $request->getMethod());
        $uri = $request->getUri();
        $this->assertEquals("/v1/customers/cus_test/attributes/tags", $uri->getPath());

        // Verify the request body contains the array
        $requestBody = json_decode((string) $request->getBody(), true);
        $this->assertEquals($tags, $requestBody['tags']);
    }

    public function testUpdateCustomAttributesWithSingleAttributeObject()
    {
        $stream = Psr7\stream_for(self::CUSTOM_ATTRIBUTES_RESPONSE);
        list($cmClient, $mockClient) = $this->getMockClient(0, [200], $stream);

        $customer = new Customer(['uuid' => 'cus_test'], $cmClient);

        // Test with single attribute object
        $attribute = ['type' => 'String', 'key' => 'channel', 'value' => 'Facebook'];

        $result = $customer->updateCustomAttributes($attribute);
        $request = $mockClient->getRequests()[0];

        $this->assertEquals("PUT", $request->getMethod());

        // Verify the request body contains the single attribute
        $requestBody = json_decode((string) $request->getBody(), true);
        $this->assertEquals($attribute, $requestBody['custom']);
    }

    /**
     * Test the exact scenario from the customer ticket
     */
    public function testCustomerTicketScenario()
    {
        $stream = Psr7\stream_for(self::CUSTOM_ATTRIBUTES_RESPONSE);
        list($cmClient, $mockClient) = $this->getMockClient(0, [200], $stream);

        $customer = new Customer(['uuid' => 'cus_test'], $cmClient);

        // Simulate customer's scenario: building array conditionally
        $attributes = [];

        // Only add attributes that have values
        $channel = 'Facebook';
        $age = 25;
        $referrer = null; // This field is empty

        if (!empty($channel)) {
            $attributes[] = ['type' => 'String', 'key' => 'channel', 'value' => $channel];
        }

        if (!empty($age)) {
            $attributes[] = ['type' => 'Integer', 'key' => 'age', 'value' => $age];
        }

        if (!empty($referrer)) {
            $attributes[] = ['type' => 'String', 'key' => 'referrer', 'value' => $referrer];
        }

        // This should now work with the new array support!
        $result = $customer->addCustomAttributes($attributes);

        $request = $mockClient->getRequests()[0];
        $this->assertEquals("POST", $request->getMethod());

        // Verify only non-empty attributes were sent
        $requestBody = json_decode((string) $request->getBody(), true);
        $this->assertCount(2, $requestBody['custom']); // Only channel and age
        $this->assertEquals($attributes, $requestBody['custom']);
    }
}
