<?php
namespace ChartMogul\Tests;

use ChartMogul\EntityNote;
use GuzzleHttp\Psr7;

class EntityNoteTest extends TestCase
{
    const NOTE_JSON = '{
      "uuid": "note_00000000-0000-0000-0000-000000000000",
      "customer_uuid": "cus_00000000-0000-0000-0000-000000000000",
      "associated_object": "customer",
      "associated_object_uuid": "cus_00000000-0000-0000-0000-000000000000",
      "type": "note",
      "text": "This is a note",
      "call_duration": 0,
      "author": "John Doe (john@example.com)",
      "created_at": "2015-06-09T13:16:00-04:00",
      "updated_at": "2015-06-09T13:16:00-04:00"
    }';

    const CONTACT_NOTE_JSON = '{
      "uuid": "note_00000000-0000-0000-0000-000000000001",
      "customer_uuid": null,
      "associated_object": "contact",
      "associated_object_uuid": "con_00000000-0000-0000-0000-000000000000",
      "type": "call",
      "text": "Call with the contact",
      "call_duration": 120,
      "author": "John Doe (john@example.com)",
      "created_at": "2015-06-09T13:16:00-04:00",
      "updated_at": "2015-06-09T13:16:00-04:00"
    }';

    const UPDATED_NOTE_JSON = '{
      "uuid": "note_00000000-0000-0000-0000-000000000000",
      "customer_uuid": "cus_00000000-0000-0000-0000-000000000000",
      "type": "note",
      "text": "This is a new note",
      "call_duration": 0,
      "author": "John Doe (john@example.com)",
      "created_at": "2015-06-09T13:16:00-04:00",
      "updated_at": "2015-06-10T13:16:00-04:00"
    }';

    const LIST_NOTES_JSON = '{
      "entries": [
        {
          "uuid": "note_00000000-0000-0000-0000-000000000000",
          "customer_uuid": "cus_00000000-0000-0000-0000-000000000000",
          "type": "note",
          "text": "This is a note",
          "call_duration": 0,
          "author": "John Doe (john@example.com)",
          "created_at": "2015-06-09T13:16:00-04:00",
          "updated_at": "2015-06-09T13:16:00-04:00"
        }
      ],
      "cursor": "cursor==",
      "has_more": true
    }';

    public function testListNotes()
    {
        $stream = Psr7\Utils::streamFor(EntityNoteTest::LIST_NOTES_JSON);
        list($cmClient, $mockClient) = $this->getMockClient(0, [200], $stream);

        $customer_uuid = "cus_00000000-0000-0000-0000-000000000000";

        $result = EntityNote::all(["customer_uuid" => $customer_uuid, "cursor" => "cursor=="], $cmClient);
        $request = $mockClient->getRequests()[0];

        $this->assertEquals("GET", $request->getMethod());
        $uri = $request->getUri();
        $this->assertEquals("/v1/notes", $uri->getPath());
        parse_str($uri->getQuery(), $query);
        $this->assertEquals($customer_uuid, $query["customer_uuid"]);
        $this->assertEquals("cursor==", $query["cursor"]);

        $this->assertTrue($result[0] instanceof EntityNote);
        $this->assertEquals("cursor==", $result->cursor);
        $this->assertEquals(true, $result->has_more);
    }

    public function testListNotesForContact()
    {
        $stream = Psr7\Utils::streamFor(EntityNoteTest::LIST_NOTES_JSON);
        list($cmClient, $mockClient) = $this->getMockClient(0, [200], $stream);

        $contact_uuid = "con_00000000-0000-0000-0000-000000000000";

        EntityNote::all(
            [
            "contact_uuid" => $contact_uuid,
            "type" => "call",
            "author_email" => "john@example.com",
            ], $cmClient
        );
        $request = $mockClient->getRequests()[0];

        parse_str($request->getUri()->getQuery(), $query);
        $this->assertEquals($contact_uuid, $query["contact_uuid"]);
        $this->assertEquals("call", $query["type"]);
        $this->assertEquals("john@example.com", $query["author_email"]);
    }

    public function testCreateNoteWithCustomerUuid()
    {
        $stream = Psr7\Utils::streamFor(EntityNoteTest::NOTE_JSON);
        list($cmClient, $mockClient) = $this->getMockClient(0, [200], $stream);

        $customer_uuid = "cus_00000000-0000-0000-0000-000000000000";

        $result = EntityNote::create(
            [
            "customer_uuid" => $customer_uuid,
            "type" => "note",
            "author_email" => "john@example.com",
            "text" => "This is a note",
            ], $cmClient
        );
        $request = $mockClient->getRequests()[0];

        $this->assertEquals("POST", $request->getMethod());
        $uri = $request->getUri();
        $this->assertEquals("/v1/notes", $uri->getPath());
        $body = json_decode((string) $request->getBody(), true);
        $this->assertEquals(
            [
            "customer_uuid" => $customer_uuid,
            "type" => "note",
            "author_email" => "john@example.com",
            "text" => "This is a note",
            ], $body
        );

        $this->assertTrue($result instanceof EntityNote);
        $this->assertEquals("note_00000000-0000-0000-0000-000000000000", $result->uuid);
        $this->assertEquals($customer_uuid, $result->customer_uuid);
        $this->assertEquals("customer", $result->associated_object);
        $this->assertEquals($customer_uuid, $result->associated_object_uuid);
        $this->assertEquals("note", $result->type);
        $this->assertEquals("This is a note", $result->text);
        $this->assertEquals(0, $result->call_duration);
        $this->assertEquals("John Doe (john@example.com)", $result->author);
        $this->assertEquals("2015-06-09T13:16:00-04:00", $result->created_at);
        $this->assertEquals("2015-06-09T13:16:00-04:00", $result->updated_at);
    }

    public function testCreateNoteWithAssociatedObjectIdentifier()
    {
        $stream = Psr7\Utils::streamFor(EntityNoteTest::CONTACT_NOTE_JSON);
        list($cmClient, $mockClient) = $this->getMockClient(0, [200], $stream);

        $contact_uuid = "con_00000000-0000-0000-0000-000000000000";
        $identifier = [
            "associated_object" => "contact",
            "method" => "uuid",
            "value" => $contact_uuid,
        ];

        $result = EntityNote::create(
            [
            "associated_object_identifier" => $identifier,
            "type" => "call",
            "text" => "Call with the contact",
            "call_duration" => 120,
            ], $cmClient
        );
        $request = $mockClient->getRequests()[0];

        $body = json_decode((string) $request->getBody(), true);
        $this->assertEquals(
            [
            "associated_object_identifier" => $identifier,
            "type" => "call",
            "text" => "Call with the contact",
            "call_duration" => 120,
            ], $body
        );

        $this->assertTrue($result instanceof EntityNote);
        $this->assertNull($result->customer_uuid);
        $this->assertEquals("contact", $result->associated_object);
        $this->assertEquals($contact_uuid, $result->associated_object_uuid);
        $this->assertEquals("call", $result->type);
        $this->assertEquals(120, $result->call_duration);
    }

    public function testRetrieveNote()
    {
        $stream = Psr7\Utils::streamFor(EntityNoteTest::NOTE_JSON);
        list($cmClient, $mockClient) = $this->getMockClient(0, [200], $stream);

        $uuid = "note_00000000-0000-0000-0000-000000000000";

        $result = EntityNote::retrieve($uuid, $cmClient);
        $request = $mockClient->getRequests()[0];

        $this->assertEquals("GET", $request->getMethod());
        $uri = $request->getUri();
        $this->assertEquals("/v1/notes/".$uuid, $uri->getPath());

        $this->assertTrue($result instanceof EntityNote);
        $this->assertEquals($uuid, $result->uuid);
    }

    public function testUpdateNote()
    {
        $stream = Psr7\Utils::streamFor(EntityNoteTest::UPDATED_NOTE_JSON);
        list($cmClient, $mockClient) = $this->getMockClient(0, [200], $stream);

        $uuid = "note_00000000-0000-0000-0000-000000000000";

        $result = EntityNote::update(
            ["note_uuid" => $uuid],
            ["text" => "This is a new note"],
            $cmClient
        );
        $request = $mockClient->getRequests()[0];

        $this->assertEquals("PATCH", $request->getMethod());
        $uri = $request->getUri();
        $this->assertEquals("/v1/notes/".$uuid, $uri->getPath());
        $this->assertEquals('{"text":"This is a new note"}', (string) $request->getBody());

        $this->assertTrue($result instanceof EntityNote);
        $this->assertEquals("This is a new note", $result->text);
    }

    public function testUpdateNoteNotModified()
    {
        list($cmClient, $mockClient) = $this->getMockClient(0, [304]);

        $uuid = "note_00000000-0000-0000-0000-000000000000";

        $result = EntityNote::update(
            ["note_uuid" => $uuid],
            ["text" => "This is a note"],
            $cmClient
        );
        $request = $mockClient->getRequests()[0];

        $this->assertEquals("PATCH", $request->getMethod());
        $this->assertTrue($result instanceof EntityNote);
        $this->assertNull($result->uuid);
        $this->assertNull($result->text);
    }

    public function testDeleteNote()
    {
        $stream = Psr7\Utils::streamFor('{"message":"Note deleted"}');
        list($cmClient, $mockClient) = $this->getMockClient(0, [202], $stream);

        $uuid = "note_00000000-0000-0000-0000-000000000000";

        $result = (new EntityNote(["uuid" => $uuid], $cmClient))->destroy();
        $request = $mockClient->getRequests()[0];

        $this->assertEquals("DELETE", $request->getMethod());
        $uri = $request->getUri();
        $this->assertEquals("/v1/notes/".$uuid, $uri->getPath());

        $this->assertTrue($result);
    }
}
