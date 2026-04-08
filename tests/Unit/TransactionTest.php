<?php
namespace ChartMogul\Tests;

use ChartMogul\Transaction;
use GuzzleHttp\Psr7;

class TransactionTest extends TestCase
{
    const TRANSACTION_JSON = '{
        "uuid": "tr_0e4de894-83c3-44d8-b406-2b0f89e67fda",
        "external_id": "ch_3St2vHDZd9drsue42v3rkVMC",
        "type": "payment",
        "date": "2026-01-24T09:14:29.000Z",
        "result": "successful",
        "amount_in_cents": null,
        "transaction_fees_in_cents": 350,
        "transaction_fees_currency": "EUR",
        "invoice_uuid": "inv_25256bdf-6d93-48fa-8df3-b5bd8ec7c514",
        "disabled": false,
        "disabled_at": null,
        "disabled_by": null
    }';

    public function testRetrieveByExternalId()
    {
        $stream = Psr7\stream_for(self::TRANSACTION_JSON);
        list($cmClient, $mockClient) = $this->getMockClient(0, [200], $stream);

        $result = Transaction::retrieveByExternalId(
            'ds_1fm3eaac-62d0-31ec-clf4-4bf0mbe81aba',
            'ch_3St2vHDZd9drsue42v3rkVMC',
            $cmClient
        );

        $request = $mockClient->getRequests()[0];
        $this->assertEquals('GET', $request->getMethod());
        $uri = $request->getUri();
        $this->assertEquals('/v1/transactions', $uri->getPath());
        parse_str($uri->getQuery(), $queryParams);
        $this->assertEquals('ds_1fm3eaac-62d0-31ec-clf4-4bf0mbe81aba', $queryParams['data_source_uuid']);
        $this->assertEquals('ch_3St2vHDZd9drsue42v3rkVMC', $queryParams['external_id']);
        $this->assertTrue($result instanceof Transaction);
        $this->assertEquals('tr_0e4de894-83c3-44d8-b406-2b0f89e67fda', $result->uuid);
    }

    public function testUpdateByExternalId()
    {
        $stream = Psr7\stream_for(self::TRANSACTION_JSON);
        list($cmClient, $mockClient) = $this->getMockClient(0, [200], $stream);

        $result = Transaction::updateByExternalId(
            'ds_1fm3eaac-62d0-31ec-clf4-4bf0mbe81aba',
            'ch_3St2vHDZd9drsue42v3rkVMC',
            ['date' => '2026-02-01T00:00:00Z'],
            $cmClient
        );

        $request = $mockClient->getRequests()[0];
        $this->assertEquals('PATCH', $request->getMethod());
        $uri = $request->getUri();
        $this->assertEquals('/v1/transactions', $uri->getPath());
        parse_str($uri->getQuery(), $queryParams);
        $this->assertEquals('ds_1fm3eaac-62d0-31ec-clf4-4bf0mbe81aba', $queryParams['data_source_uuid']);
        $this->assertEquals('ch_3St2vHDZd9drsue42v3rkVMC', $queryParams['external_id']);

        $body = json_decode((string) $request->getBody(), true);
        $this->assertEquals(['date' => '2026-02-01T00:00:00Z'], $body);
        $this->assertTrue($result instanceof Transaction);
    }

    public function testDestroyByExternalId()
    {
        list($cmClient, $mockClient) = $this->getMockClient(0, [204]);

        $result = Transaction::destroyByExternalId(
            'ds_1fm3eaac-62d0-31ec-clf4-4bf0mbe81aba',
            'ch_3St2vHDZd9drsue42v3rkVMC',
            $cmClient
        );

        $request = $mockClient->getRequests()[0];
        $this->assertEquals('DELETE', $request->getMethod());
        $uri = $request->getUri();
        $this->assertEquals('/v1/transactions', $uri->getPath());
        parse_str($uri->getQuery(), $queryParams);
        $this->assertEquals('ds_1fm3eaac-62d0-31ec-clf4-4bf0mbe81aba', $queryParams['data_source_uuid']);
        $this->assertEquals('ch_3St2vHDZd9drsue42v3rkVMC', $queryParams['external_id']);
        $this->assertTrue($result);
    }

    public function testToggleDisabledByExternalId()
    {
        $stream = Psr7\stream_for(self::TRANSACTION_JSON);
        list($cmClient, $mockClient) = $this->getMockClient(0, [200], $stream);

        $result = Transaction::toggleDisabledByExternalId(
            'ds_1fm3eaac-62d0-31ec-clf4-4bf0mbe81aba',
            'ch_3St2vHDZd9drsue42v3rkVMC',
            true,
            $cmClient
        );

        $request = $mockClient->getRequests()[0];
        $this->assertEquals('PATCH', $request->getMethod());
        $uri = $request->getUri();
        $this->assertEquals('/v1/transactions/disabled_state', $uri->getPath());
        parse_str($uri->getQuery(), $queryParams);
        $this->assertEquals('ds_1fm3eaac-62d0-31ec-clf4-4bf0mbe81aba', $queryParams['data_source_uuid']);
        $this->assertEquals('ch_3St2vHDZd9drsue42v3rkVMC', $queryParams['external_id']);

        $body = json_decode((string) $request->getBody(), true);
        $this->assertEquals(['disabled' => true], $body);
        $this->assertTrue($result instanceof Transaction);
    }

    public function testRetrieveByUuid()
    {
        $stream = Psr7\stream_for(self::TRANSACTION_JSON);
        list($cmClient, $mockClient) = $this->getMockClient(0, [200], $stream);

        $uuid = 'tr_0e4de894-83c3-44d8-b406-2b0f89e67fda';
        $result = Transaction::retrieve($uuid, $cmClient);

        $request = $mockClient->getRequests()[0];
        $this->assertEquals('GET', $request->getMethod());
        $uri = $request->getUri();
        $this->assertEquals('/v1/transactions/' . $uuid, $uri->getPath());
        $this->assertTrue($result instanceof Transaction);
        $this->assertEquals($uuid, $result->uuid);
    }

    public function testUpdateByUuid()
    {
        $stream = Psr7\stream_for(self::TRANSACTION_JSON);
        list($cmClient, $mockClient) = $this->getMockClient(0, [200], $stream);

        $uuid = 'tr_0e4de894-83c3-44d8-b406-2b0f89e67fda';
        $result = Transaction::update(
            ['transaction_uuid' => $uuid],
            ['date' => '2026-02-01T00:00:00Z'],
            $cmClient
        );

        $request = $mockClient->getRequests()[0];
        $this->assertEquals('PATCH', $request->getMethod());
        $uri = $request->getUri();
        $this->assertEquals('/v1/transactions/' . $uuid, $uri->getPath());
        $this->assertTrue($result instanceof Transaction);

        $body = json_decode((string) $request->getBody(), true);
        $this->assertEquals(['date' => '2026-02-01T00:00:00Z'], $body);
    }

    public function testDestroyByUuid()
    {
        list($cmClient, $mockClient) = $this->getMockClient(0, [204]);

        $result = (new Transaction(['uuid' => 'tr_0e4de894-83c3-44d8-b406-2b0f89e67fda'], $cmClient))->destroy();

        $request = $mockClient->getRequests()[0];
        $this->assertEquals('DELETE', $request->getMethod());
        $uri = $request->getUri();
        $this->assertEquals('/v1/transactions/tr_0e4de894-83c3-44d8-b406-2b0f89e67fda', $uri->getPath());
        $this->assertTrue($result);
    }

    public function testDisableByUuid()
    {
        $stream = Psr7\stream_for(self::TRANSACTION_JSON);
        list($cmClient, $mockClient) = $this->getMockClient(0, [200], $stream);

        $uuid = 'tr_0e4de894-83c3-44d8-b406-2b0f89e67fda';
        $result = Transaction::disable($uuid, true, $cmClient);

        $request = $mockClient->getRequests()[0];
        $this->assertEquals('PATCH', $request->getMethod());
        $uri = $request->getUri();
        $this->assertEquals('/v1/transactions/' . $uuid . '/disabled_state', $uri->getPath());
        $this->assertTrue($result instanceof Transaction);

        $body = json_decode((string) $request->getBody(), true);
        $this->assertEquals(['disabled' => true], $body);
    }
}
