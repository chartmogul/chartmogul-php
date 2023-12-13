<?php
namespace ChartMogul\Tests;

use ChartMogul\Http\Client;
use ChartMogul\CustomerNote;
use ChartMogul\Resource\Collection;
use ChartMogul\Exceptions\ChartMogulException;
use GuzzleHttp\Psr7;
use GuzzleHttp\Psr7\Response;

class CustomerNoteTest extends TestCase
{
    const NOTE_JSON= '{
      "uuid": "note_00000000-0000-0000-0000-000000000000",
      "customer_uuid": "cus_00000000-0000-0000-0000-000000000000",
      "type": "note",
      "author": "John Doe (john@example.com)",
      "text": "This is a note",
      "created_at": "2015-06-09T13:16:00-04:00",
      "updated_at": "2015-06-09T13:16:00-04:00"
    }';

    const UPDATED_NOTE_JSON= '{
      "uuid": "note_00000000-0000-0000-0000-000000000000",
      "customer_uuid": "cus_00000000-0000-0000-0000-000000000000",
      "type": "note",
      "author": "John Doe (john@example.com)",
      "text": "This is a new note",
      "created_at": "2015-06-09T13:16:00-04:00",
      "updated_at": "2015-06-09T13:16:00-04:00"
    }';

    const LIST_NOTES_JSON= '{
        "entries": [
          {
            "uuid": "note_00000000-0000-0000-0000-000000000000",
            "customer_uuid": "cus_00000000-0000-0000-0000-000000000000",
            "type": "note",
            "author": "John Doe (john@example.com)",
            "text": "This is a note",
            "created_at": "2015-06-09T13:16:00-04:00",
            "updated_at": "2015-06-09T13:16:00-04:00"
          }
        ],
        "cursor": "cursor==",
        "has_more": true
      }';

    public function testListNotes()
    {
        $stream = Psr7\stream_for(CustomerNoteTest::LIST_NOTES_JSON);
        list($cmClient, $mockClient) = $this->getMockClient(0, [200], $stream);

        $uuid = "cus_00000000-0000-0000-0000-000000000000";

        $result = CustomerNote::all(["customer_uuid" => $uuid], $cmClient);
        $request = $mockClient->getRequests()[0];

        $this->assertEquals("GET", $request->getMethod());
        $uri = $request->getUri();
        $this->assertEquals("/v1/customer_notes", $uri->getPath());

        $this->assertTrue($result[0] instanceof CustomerNote);
        $this->assertEquals("cursor==", $result->cursor);
        $this->assertEquals(true, $result->has_more);
    }

    public function testCreateNote()
    {
        $stream = Psr7\stream_for(CustomerNoteTest::NOTE_JSON);
        list($cmClient, $mockClient) = $this->getMockClient(0, [200], $stream);

        $uuid = "cus_00000000-0000-0000-0000-000000000000";

        $result = CustomerNote::create(
            [
            "customer_uuid" => $uuid,
            "type" => "note",
            "author_email" => "john@example.com",
            "text" => "This is a note",
            ]
            , $cmClient
        );
        $request = $mockClient->getRequests()[0];

        $this->assertEquals("POST", $request->getMethod());
        $uri = $request->getUri();
        $this->assertEquals("/v1/customer_notes", $uri->getPath());

        $this->assertTrue($result instanceof CustomerNote);
        $this->assertEquals("note_00000000-0000-0000-0000-000000000000", $result->uuid);
    }

    public function testRetrieveNote()
    {
        $stream = Psr7\stream_for(CustomerNoteTest::NOTE_JSON);
        list($cmClient, $mockClient) = $this->getMockClient(0, [200], $stream);

        $uuid = "con_00000000-0000-0000-0000-000000000000";

        $result = CustomerNote::retrieve($uuid, $cmClient);
        $request = $mockClient->getRequests()[0];

        $this->assertEquals("GET", $request->getMethod());
        $uri = $request->getUri();
        $this->assertEquals("/v1/customer_notes/".$uuid, $uri->getPath());

        $this->assertTrue($result instanceof CustomerNote);
        $this->assertEquals("note_00000000-0000-0000-0000-000000000000", $result->uuid);
    }

    public function testUpdateNote()
    {
        $stream = Psr7\stream_for(CustomerNoteTest::UPDATED_NOTE_JSON);
        list($cmClient, $mockClient) = $this->getMockClient(0, [200], $stream);

        $uuid = "note_00000000-0000-0000-0000-000000000000";

        $result = CustomerNote::update(
            [
            "note_uuid" => $uuid,
            ], [
            "text" => "This is a new note",
            ], $cmClient
        );
        $request = $mockClient->getRequests()[0];

        $this->assertEquals("PATCH", $request->getMethod());
        $uri = $request->getUri();
        $this->assertEquals("/v1/customer_notes/".$uuid, $uri->getPath());

        $this->assertTrue($result instanceof CustomerNote);
        $this->assertEquals("This is a new note", $result->text);
    }

    public function testDeleteNote()
    {
        $stream = Psr7\stream_for("{}");
        list($cmClient, $mockClient) = $this->getMockClient(0, [204], $stream);

        $uuid = "note_00000000-0000-0000-0000-000000000000";

        $result = (new CustomerNote(["uuid" => $uuid], $cmClient))->destroy();
        $request = $mockClient->getRequests()[0];

        $this->assertEquals("DELETE", $request->getMethod());
        $uri = $request->getUri();
        $this->assertEquals("/v1/customer_notes/".$uuid, $uri->getPath());

        $this->assertEquals("{}", $result);
    }
}
