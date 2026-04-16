<?php
namespace ChartMogul\Tests;

use ChartMogul\LineItem;
use GuzzleHttp\Psr7;

class LineItemTest extends TestCase
{
    const LINE_ITEM_JSON = '{
        "uuid": "li_592f4699-107b-41b9-b7bc-a2aa2ca7a67b",
        "external_id": "il_1Srh42DZd9drsue4U2FQKjsb",
        "type": "subscription",
        "amount_in_cents": 10000,
        "quantity": 1,
        "discount_code": "",
        "discount_amount_in_cents": 0,
        "tax_amount_in_cents": 0,
        "transaction_fees_in_cents": 0,
        "account_code": "-",
        "plan_uuid": "pl_c9e8c6fa-c2a0-4b04-a4c5-54ce9f381a54",
        "plan_external_id": "price_1QK8toDZd9drsue4cpB6eafx",
        "invoice_uuid": "inv_25256bdf-6d93-48fa-8df3-b5bd8ec7c514",
        "disabled": false,
        "disabled_at": null,
        "disabled_by": null
    }';

    public function testRetrieveByExternalId()
    {
        $stream = Psr7\stream_for(self::LINE_ITEM_JSON);
        list($cmClient, $mockClient) = $this->getMockClient(0, [200], $stream);

        $result = LineItem::retrieveByExternalId(
            'ds_1fm3eaac-62d0-31ec-clf4-4bf0mbe81aba',
            'il_1Srh42DZd9drsue4U2FQKjsb',
            $cmClient
        );

        $request = $mockClient->getRequests()[0];
        $this->assertEquals('GET', $request->getMethod());
        $uri = $request->getUri();
        $this->assertEquals('/v1/line_items', $uri->getPath());
        parse_str($uri->getQuery(), $queryParams);
        $this->assertEquals('ds_1fm3eaac-62d0-31ec-clf4-4bf0mbe81aba', $queryParams['data_source_uuid']);
        $this->assertEquals('il_1Srh42DZd9drsue4U2FQKjsb', $queryParams['external_id']);
        $this->assertTrue($result instanceof LineItem);
        $this->assertEquals('li_592f4699-107b-41b9-b7bc-a2aa2ca7a67b', $result->uuid);
    }

    public function testUpdateByExternalId()
    {
        $stream = Psr7\stream_for(self::LINE_ITEM_JSON);
        list($cmClient, $mockClient) = $this->getMockClient(0, [200], $stream);

        $result = LineItem::updateByExternalId(
            'ds_1fm3eaac-62d0-31ec-clf4-4bf0mbe81aba',
            'il_1Srh42DZd9drsue4U2FQKjsb',
            ['amount_in_cents' => 5000],
            $cmClient
        );

        $request = $mockClient->getRequests()[0];
        $this->assertEquals('PATCH', $request->getMethod());
        $uri = $request->getUri();
        $this->assertEquals('/v1/line_items', $uri->getPath());
        parse_str($uri->getQuery(), $queryParams);
        $this->assertEquals('ds_1fm3eaac-62d0-31ec-clf4-4bf0mbe81aba', $queryParams['data_source_uuid']);
        $this->assertEquals('il_1Srh42DZd9drsue4U2FQKjsb', $queryParams['external_id']);

        $body = json_decode((string) $request->getBody(), true);
        $this->assertEquals(['amount_in_cents' => 5000], $body);
        $this->assertTrue($result instanceof LineItem);
    }

    public function testDestroyByExternalId()
    {
        list($cmClient, $mockClient) = $this->getMockClient(0, [204]);

        $result = LineItem::destroyByExternalId(
            'ds_1fm3eaac-62d0-31ec-clf4-4bf0mbe81aba',
            'il_1Srh42DZd9drsue4U2FQKjsb',
            $cmClient
        );

        $request = $mockClient->getRequests()[0];
        $this->assertEquals('DELETE', $request->getMethod());
        $uri = $request->getUri();
        $this->assertEquals('/v1/line_items', $uri->getPath());
        parse_str($uri->getQuery(), $queryParams);
        $this->assertEquals('ds_1fm3eaac-62d0-31ec-clf4-4bf0mbe81aba', $queryParams['data_source_uuid']);
        $this->assertEquals('il_1Srh42DZd9drsue4U2FQKjsb', $queryParams['external_id']);
        $this->assertTrue($result);
    }

    public function testToggleDisabledByExternalId()
    {
        $stream = Psr7\stream_for(self::LINE_ITEM_JSON);
        list($cmClient, $mockClient) = $this->getMockClient(0, [200], $stream);

        $result = LineItem::toggleDisabledByExternalId(
            'ds_1fm3eaac-62d0-31ec-clf4-4bf0mbe81aba',
            'il_1Srh42DZd9drsue4U2FQKjsb',
            true,
            $cmClient
        );

        $request = $mockClient->getRequests()[0];
        $this->assertEquals('PATCH', $request->getMethod());
        $uri = $request->getUri();
        $this->assertEquals('/v1/line_items/disabled_state', $uri->getPath());
        parse_str($uri->getQuery(), $queryParams);
        $this->assertEquals('ds_1fm3eaac-62d0-31ec-clf4-4bf0mbe81aba', $queryParams['data_source_uuid']);
        $this->assertEquals('il_1Srh42DZd9drsue4U2FQKjsb', $queryParams['external_id']);

        $body = json_decode((string) $request->getBody(), true);
        $this->assertEquals(['disabled' => true], $body);
        $this->assertTrue($result instanceof LineItem);
    }

    public function testRetrieveByUuid()
    {
        $stream = Psr7\stream_for(self::LINE_ITEM_JSON);
        list($cmClient, $mockClient) = $this->getMockClient(0, [200], $stream);

        $uuid = 'li_592f4699-107b-41b9-b7bc-a2aa2ca7a67b';
        $result = LineItem::retrieve($uuid, $cmClient);

        $request = $mockClient->getRequests()[0];
        $this->assertEquals('GET', $request->getMethod());
        $uri = $request->getUri();
        $this->assertEquals('/v1/line_items/' . $uuid, $uri->getPath());
        $this->assertTrue($result instanceof LineItem);
        $this->assertEquals($uuid, $result->uuid);
    }

    public function testUpdateByUuid()
    {
        $stream = Psr7\stream_for(self::LINE_ITEM_JSON);
        list($cmClient, $mockClient) = $this->getMockClient(0, [200], $stream);

        $uuid = 'li_592f4699-107b-41b9-b7bc-a2aa2ca7a67b';
        $result = LineItem::update(
            ['line_item_uuid' => $uuid],
            ['amount_in_cents' => 5000],
            $cmClient
        );

        $request = $mockClient->getRequests()[0];
        $this->assertEquals('PATCH', $request->getMethod());
        $uri = $request->getUri();
        $this->assertEquals('/v1/line_items/' . $uuid, $uri->getPath());
        $this->assertTrue($result instanceof LineItem);

        $body = json_decode((string) $request->getBody(), true);
        $this->assertEquals(['amount_in_cents' => 5000], $body);
    }

    public function testDestroyByUuid()
    {
        list($cmClient, $mockClient) = $this->getMockClient(0, [204]);

        $result = (new LineItem(['uuid' => 'li_592f4699-107b-41b9-b7bc-a2aa2ca7a67b'], $cmClient))->destroy();

        $request = $mockClient->getRequests()[0];
        $this->assertEquals('DELETE', $request->getMethod());
        $uri = $request->getUri();
        $this->assertEquals('/v1/line_items/li_592f4699-107b-41b9-b7bc-a2aa2ca7a67b', $uri->getPath());
        $this->assertTrue($result);
    }

    public function testToggleDisabledByUuid()
    {
        $stream = Psr7\stream_for(self::LINE_ITEM_JSON);
        list($cmClient, $mockClient) = $this->getMockClient(0, [200], $stream);

        $uuid = 'li_592f4699-107b-41b9-b7bc-a2aa2ca7a67b';
        $result = LineItem::toggleDisabled($uuid, true, $cmClient);

        $request = $mockClient->getRequests()[0];
        $this->assertEquals('PATCH', $request->getMethod());
        $uri = $request->getUri();
        $this->assertEquals('/v1/line_items/' . $uuid . '/disabled_state', $uri->getPath());
        $this->assertTrue($result instanceof LineItem);

        $body = json_decode((string) $request->getBody(), true);
        $this->assertEquals(['disabled' => true], $body);
    }

    public function testToggleEnabledByUuid()
    {
        $stream = Psr7\stream_for(self::LINE_ITEM_JSON);
        list($cmClient, $mockClient) = $this->getMockClient(0, [200], $stream);

        $uuid = 'li_592f4699-107b-41b9-b7bc-a2aa2ca7a67b';
        $result = LineItem::toggleDisabled($uuid, false, $cmClient);

        $request = $mockClient->getRequests()[0];
        $this->assertEquals('PATCH', $request->getMethod());
        $uri = $request->getUri();
        $this->assertEquals('/v1/line_items/' . $uuid . '/disabled_state', $uri->getPath());
        $this->assertTrue($result instanceof LineItem);

        $body = json_decode((string) $request->getBody(), true);
        $this->assertEquals(['disabled' => false], $body);
    }
}
