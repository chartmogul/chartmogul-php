<?php
namespace ChartMogul\Tests;

use ChartMogul\Http\Client;
use ChartMogul\CustomerNote;
use ChartMogul\Resource\Collection;
use ChartMogul\Exceptions\ChartMogulException;
use GuzzleHttp\Psr7;
use GuzzleHttp\Psr7\Response;
use function GuzzleHttp\Psr7;

class CustomerNoteTest extends TestCase
{
    const LIST_CUSTOMER_NOTES_JSON= '{
      "entries": [
        {
            "uuid": "note_3930e040-901d-11ee-84e9-8f5c5581caa5",
            "customer_uuid": "cus_7edc7ed4-bcb4-11ed-9491-87c7772e5602",
            "type": "call",
            "text": "Call log",
            "call_duration": 60,
            "author": "Adam (adam@smith.com)",
            "created_at": "2023-12-01T07:42:48.300Z",
            "updated_at": "2023-12-01T07:42:48.300Z"
        }
      ],
      "cursor": "cursor==",
      "has_more": true
    }';

    const CUSTOMER_NOTE_JSON= '{
        "uuid": "note_3930e040-901d-11ee-84e9-8f5c5581caa5",
        "customer_uuid": "cus_7edc7ed4-bcb4-11ed-9491-87c7772e5602",
        "type": "call",
        "text": "Call log",
        "call_duration": 60,
        "author": "Adam (adam@smith.com)",
        "created_at": "2023-12-01T07:42:48.300Z",
        "updated_at": "2023-12-01T07:42:48.300Z"
    }';

    const UPDATED_CUSTOMER_NOTE_JSON = '{
        "uuid": "note_3930e040-901d-11ee-84e9-8f5c5581caa5",
        "customer_uuid": "cus_7edc7ed4-bcb4-11ed-9491-87c7772e5602",
        "type": "call",
        "text": "Updated call log",
        "call_duration": 60,
        "author": "Adam (adam@smith.com)",
        "created_at": "2023-12-01T07:42:48.300Z",
        "updated_at": "2023-12-01T07:42:48.300Z"
    }';

    public function testRetrieveCustomerNote()
    {
        $stream = Psr7\stream_for(CustomerNoteTest::CUSTOMER_NOTE_JSON);
        list($cmClient, $mockClient) = $this->getMockClient(0, [200], $stream);

        $uuid = 'note_3930e040-901d-11ee-84e9-8f5c5581caa5';
        $customer_uuid = 'cus_7edc7ed4-bcb4-11ed-9491-87c7772e5602';

        $result = CustomerNote::retrieve([
            'customer_uuid' => $customer_uuid,
            'note_uuid' => $uuid,
        ], $cmClient);
        $request = $mockClient->getRequests()[0];

        $this->assertEquals('GET', $request->getMethod());
        $uri = $request->getUri();
        $this->assertEquals('', $uri->getQuery());
        $this->assertEquals('/v1/customers/'.$customer_uuid.'/notes/'.$uuid, $uri->getPath());

        $this->assertTrue($result instanceof CustomerNote);
        $this->assertEquals($uuid, $result->uuid);
    }

    public function testCreateCustomerNote()
    {
        $stream = Psr7\stream_for(CustomerNoteTest::CUSTOMER_NOTE_JSON);
        list($cmClient, $mockClient) = $this->getMockClient(0, [200], $stream);

        $customer_uuid = 'cus_7edc7ed4-bcb4-11ed-9491-87c7772e5602';

        $result = CustomerNote::create([
            'customer_uuid' => $customer_uuid,
            'type' => 'call',
            'text' => 'Call log',
            'call_duration' => 60,
            'author_email' => 'adam@smith.com'
        ], $cmClient);
        $request = $mockClient->getRequests()[0];

        $this->assertEquals('POST', $request->getMethod());
        $uri = $request->getUri();
        $this->assertEquals('', $uri->getQuery());
        $this->assertEquals('/v1/customers/'.$customer_uuid.'/notes', $uri->getPath());

        $this->assertTrue($result instanceof CustomerNote);
        $this->assertEquals('Adam (adam@smith.com)', $result->author);
    }

    public function testListCustomerNotes()
    {
        $stream = Psr7\stream_for(CustomerNoteTest::LIST_CUSTOMER_NOTES_JSON);
        list($cmClient, $mockClient) = $this->getMockClient(0, [200], $stream);

        $customer_uuid = 'cus_7edc7ed4-bcb4-11ed-9491-87c7772e5602';
        $result = CustomerNote::all(['customer_uuid' => $customer_uuid], $cmClient);
        $request = $mockClient->getRequests()[0];

        $this->assertEquals('GET', $request->getMethod());
        $uri = $request->getUri();
        $this->assertEquals('', $uri->getQuery());
        $this->assertEquals('/v1/customers/'.$customer_uuid.'/notes', $uri->getPath());

        $this->assertTrue($result[0] instanceof CustomerNote);
        $this->assertEquals('cursor==', $result->cursor);
        $this->assertEquals(true, $result->has_more);
    }

    public function testUpdateCustomerNote()
    {
        $stream = Psr7\stream_for(CustomerNoteTest::UPDATED_CUSTOMER_NOTE_JSON);
        list($cmClient, $mockClient) = $this->getMockClient(0, [200], $stream);

        $uuid = 'note_3930e040-901d-11ee-84e9-8f5c5581caa5';
        $customer_uuid = 'cus_7edc7ed4-bcb4-11ed-9491-87c7772e5602';

        $result = CustomerNote::update([
            'customer_uuid' => $customer_uuid,
            'note_uuid' => $uuid,
        ], [
            'text' => 'Updated call log',
        ], $cmClient);
        $request = $mockClient->getRequests()[0];

        $this->assertEquals('PATCH', $request->getMethod());
        $uri = $request->getUri();
        $this->assertEquals('', $uri->getQuery());
        $this->assertEquals('/v1/customers/'.$customer_uuid.'/notes/'.$uuid, $uri->getPath());

        $this->assertTrue($result instanceof CustomerNote);
        $this->assertEquals('Updated call log', $result->text);
    }

    public function testDeleteCustomerNote()
    {
        $stream = Psr7\stream_for('{}');
        list($cmClient, $mockClient) = $this->getMockClient(0, [200], $stream);

        $uuid = 'note_3930e040-901d-11ee-84e9-8f5c5581caa5';
        $customer_uuid = 'cus_7edc7ed4-bcb4-11ed-9491-87c7772e5602';

        $result = (new CustomerNote([
            'customer_uuid' => $customer_uuid,
            'uuid' => $uuid
        ], $cmClient))->destroy();
        $request = $mockClient->getRequests()[0];

        $this->assertEquals('DELETE', $request->getMethod());
        $uri = $request->getUri();
        $this->assertEquals('', $uri->getQuery());
        $this->assertEquals('/v1/customers/'.$customer_uuid.'/notes/'.$uuid, $uri->getPath());

        $this->assertEquals('{}', $result);
    }
}
