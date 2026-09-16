<?php
namespace ChartMogul\Tests;

use ChartMogul\Http\Client;
use ChartMogul\Contact;
use ChartMogul\EntityNote;
use ChartMogul\Task;
use ChartMogul\Resource\Collection;
use ChartMogul\Exceptions\ChartMogulException;
use GuzzleHttp\Psr7;
use GuzzleHttp\Psr7\Response;

class ContactTest extends TestCase
{
    const LIST_CONTACTS_JSON= '{
      "entries": [
        {
          "uuid": "con_00000000-0000-0000-0000-000000000000",
          "customer_uuid": "cus_00000000-0000-0000-0000-000000000000",
          "customer_external_id": "123",
          "external_id": null,
          "data_source_uuid": "ds_00000000-0000-0000-0000-000000000000",
          "position": 1,
          "first_name": "Adam",
          "last_name": "Smith",
          "title": "CEO",
          "email": "adam@smith.com",
          "phone": "Lead",
          "linked_in": null,
          "twitter": null,
          "notes": null,
          "custom": {
            "Facebook": "https://www.facebook.com/adam.smith/",
            "date_of_birth": "1985-01-22"
          }
        }
      ],
      "cursor": "cursor==",
      "has_more": true
    }';

    const CONTACT_JSON= '{
      "uuid": "con_00000000-0000-0000-0000-000000000000",
      "customer_uuid": "cus_00000000-0000-0000-0000-000000000000",
      "customer_external_id": "customer_001",
      "external_id": "contact_external_id_001",
      "data_source_uuid": "ds_00000000-0000-0000-0000-000000000000",
      "position": 9,
      "first_name": "Adam",
      "last_name": "Smith",
      "title": "CEO",
      "email": "adam@example.com",
      "phone": "+1234567890",
      "linked_in": "https://linkedin.com/linkedin",
      "twitter": "https://twitter.com/twitter",
      "notes": "Heading\nBody\nFooter",
      "custom": {
        "Facebook": "https://www.facebook.com/adam.smith",
        "date_of_birth": "1985-01-22"
      }
    }';

    const CONTACT_NULL_EXTERNAL_ID_JSON= '{
      "uuid": "con_00000000-0000-0000-0000-000000000000",
      "customer_uuid": "cus_00000000-0000-0000-0000-000000000000",
      "customer_external_id": "customer_001",
      "external_id": null,
      "data_source_uuid": "ds_00000000-0000-0000-0000-000000000000",
      "position": 9,
      "first_name": "Adam",
      "last_name": "Smith",
      "title": "CEO",
      "email": "adam@example.com",
      "phone": "+1234567890",
      "linked_in": "https://linkedin.com/linkedin",
      "twitter": "https://twitter.com/twitter",
      "notes": "Heading\nBody\nFooter",
      "custom": {
        "Facebook": "https://www.facebook.com/adam.smith",
        "date_of_birth": "1985-01-22"
      }
    }';

    const UPDATED_CONTACT_JSON = '{
      "uuid": "con_00000000-0000-0000-0000-000000000000",
      "customer_uuid": "cus_00000000-0000-0000-0000-000000000000",
      "customer_external_id": "customer_001",
      "external_id": "contact_external_id_002",
      "data_source_uuid": "ds_00000000-0000-0000-0000-000000000000",
      "first_name": "Bill",
      "last_name": "Thompson",
      "position": 10,
      "title": "CTO",
      "email": "bill@example.com",
      "phone": "+9876543210",
      "linked_in": "https://linkedin.com/bill-linkedin",
      "twitter": "https://twitter.com/bill-twitter",
      "notes": "New Heading\nNew Body\nNew Footer",
      "custom": [
        { "key": "Facebook", "value": "https://www.facebook.com/bill.thompson" },
        { "key": "date_of_birth", "value": "1990-01-01" }
      ]
    }';

    const STANDALONE_CONTACT_JSON = '{
      "uuid": "con_00000000-0000-0000-0000-000000000002",
      "customer_uuid": null,
      "customer_external_id": null,
      "external_id": null,
      "data_source_uuid": null,
      "position": 1,
      "first_name": "Adam",
      "last_name": "Smith",
      "title": null,
      "email": "adam@example.com",
      "phone": null,
      "linked_in": null,
      "twitter": null,
      "notes": null,
      "last_seen": "2025-01-01T00:00:00.000Z",
      "custom": {}
    }';

    const LIST_TASKS_JSON = '{
      "entries": [
        {
          "task_uuid": "00000000-0000-0000-0000-000000000002",
          "customer_uuid": null,
          "associated_object": "contact",
          "associated_object_uuid": "con_00000000-0000-0000-0000-000000000000",
          "task_details": "Call the contact back.",
          "assignee": "customer@example.com",
          "due_date": "2025-04-30T00:00:00Z",
          "completed_at": null,
          "created_at": "2025-04-01T12:00:00.000Z",
          "updated_at": "2025-04-01T12:00:00.000Z"
        }
      ],
      "cursor": "cursor==",
      "has_more": true
    }';

    const TASK_JSON = '{
      "task_uuid": "00000000-0000-0000-0000-000000000002",
      "customer_uuid": null,
      "associated_object": "contact",
      "associated_object_uuid": "con_00000000-0000-0000-0000-000000000000",
      "task_details": "Call the contact back.",
      "assignee": "customer@example.com",
      "due_date": "2025-04-30T00:00:00Z",
      "completed_at": null,
      "created_at": "2025-04-01T12:00:00.000Z",
      "updated_at": "2025-04-01T12:00:00.000Z"
    }';

    const LIST_ENTITY_NOTES_JSON = '{
      "entries": [
        {
          "uuid": "note_00000000-0000-0000-0000-000000000001",
          "customer_uuid": null,
          "associated_object": "contact",
          "associated_object_uuid": "con_00000000-0000-0000-0000-000000000000",
          "type": "note",
          "text": "This is a contact note",
          "call_duration": 0,
          "author": "John Doe (john@example.com)",
          "created_at": "2015-06-09T13:16:00-04:00",
          "updated_at": "2015-06-09T13:16:00-04:00"
        }
      ],
      "cursor": "cursor==",
      "has_more": true
    }';

    const ENTITY_NOTE_JSON = '{
      "uuid": "note_00000000-0000-0000-0000-000000000001",
      "customer_uuid": null,
      "associated_object": "contact",
      "associated_object_uuid": "con_00000000-0000-0000-0000-000000000000",
      "type": "note",
      "text": "This is a contact note",
      "call_duration": 0,
      "author": "John Doe (john@example.com)",
      "created_at": "2015-06-09T13:16:00-04:00",
      "updated_at": "2015-06-09T13:16:00-04:00"
    }';

    public function testRetrieveContact()
    {
        $stream = Psr7\Utils::streamFor(ContactTest::CONTACT_JSON);
        list($cmClient, $mockClient) = $this->getMockClient(0, [200], $stream);

        $uuid = "con_00000000-0000-0000-0000-000000000000";

        $result = Contact::retrieve($uuid, $cmClient);
        $request = $mockClient->getRequests()[0];

        $this->assertEquals("GET", $request->getMethod());
        $uri = $request->getUri();
        $this->assertEquals("", $uri->getQuery());
        $this->assertEquals("/v1/contacts/".$uuid, $uri->getPath());

        $this->assertTrue($result instanceof Contact);
        $this->assertEquals($uuid, $result->uuid);
        $this->assertEquals("contact_external_id_001", $result->external_id);
    }

    public function testCreateContact()
    {
        $stream = Psr7\Utils::streamFor(ContactTest::CONTACT_JSON);
        list($cmClient, $mockClient) = $this->getMockClient(0, [200], $stream);

        $result = Contact::create(
            [
            "customer_uuid" => "cus_00000000-0000-0000-0000-000000000000",
            "data_source_uuid" => "ds_00000000-0000-0000-0000-000000000000",
            "external_id" => "contact_external_id_001",
            "first_name" => "Adam",
            "last_name" => "Smith",
            "position" => 9,
            "title" => "CEO",
            "email" => "adam@example.com",
            "phone" => "+1234567890",
            "linked_in" => "https://linkedin.com/linkedin",
            "twitter" => "https://twitter.com/twitter",
            "notes" => "Heading\nBody\nFooter",
            "custom" => [
              [ "key" => "Facebook", "value" => "https://www.facebook.com/adam.smith" ],
              [ "key" => "date_of_birth", "value" => "1985-01-22" ]
            ],
            ], $cmClient
        );
        $request = $mockClient->getRequests()[0];

        $this->assertEquals("POST", $request->getMethod());
        $uri = $request->getUri();
        $this->assertEquals("", $uri->getQuery());
        $this->assertEquals("/v1/contacts", $uri->getPath());

        $this->assertTrue($result instanceof Contact);
        $this->assertEquals("Adam", $result->first_name);
        $this->assertEquals("contact_external_id_001", $result->external_id);
    }

    public function testCreateContactWithNullExternalId()
    {
        $stream = Psr7\Utils::streamFor(ContactTest::CONTACT_NULL_EXTERNAL_ID_JSON);
        list($cmClient, $mockClient) = $this->getMockClient(0, [200], $stream);

        $result = Contact::create(
            [
            "customer_uuid" => "cus_00000000-0000-0000-0000-000000000000",
            "data_source_uuid" => "ds_00000000-0000-0000-0000-000000000000",
            "first_name" => "Adam",
            "external_id" => null,
            ], $cmClient
        );

        $this->assertTrue($result instanceof Contact);
        $this->assertNull($result->external_id);
    }

    public function testCreateContactWithoutExternalId()
    {
        $stream = Psr7\Utils::streamFor(ContactTest::CONTACT_NULL_EXTERNAL_ID_JSON);
        list($cmClient, $mockClient) = $this->getMockClient(0, [200], $stream);

        $result = Contact::create(
            [
            "customer_uuid" => "cus_00000000-0000-0000-0000-000000000000",
            "data_source_uuid" => "ds_00000000-0000-0000-0000-000000000000",
            "first_name" => "Adam",
            ], $cmClient
        );

        $this->assertTrue($result instanceof Contact);
        $this->assertNull($result->external_id);
    }

    public function testListContacts()
    {
        $stream = Psr7\Utils::streamFor(ContactTest::LIST_CONTACTS_JSON);
        list($cmClient, $mockClient) = $this->getMockClient(0, [200], $stream);

        $result = Contact::all([], $cmClient);
        $request = $mockClient->getRequests()[0];

        $this->assertEquals("GET", $request->getMethod());
        $uri = $request->getUri();
        $this->assertEquals("", $uri->getQuery());
        $this->assertEquals("/v1/contacts", $uri->getPath());

        $this->assertTrue($result[0] instanceof Contact);
        $this->assertEquals("cursor==", $result->cursor);
        $this->assertEquals(true, $result->has_more);
    }

    public function testUpdateContact()
    {
        $stream = Psr7\Utils::streamFor(ContactTest::UPDATED_CONTACT_JSON);
        list($cmClient, $mockClient) = $this->getMockClient(0, [200], $stream);

        $uuid = "con_00000000-0000-0000-0000-000000000000";

        $result = Contact::update(
            [
            "contact_uuid" => $uuid,
            ], [
            "external_id" => "contact_external_id_002",
            "first_name" => "Bill",
            "last_name" => "Thomposon",
            "position" => 10,
            "title" => "CTO",
            "email" => "bill@example.com",
            "phone" => "+987654321",
            "linked_in" => "https://linkedin.com/bill-linkedin",
            "twitter" => "https://twitter.com/bill-twitter",
            "notes" => "New Heading\nBody\nFooter",
            "custom" => [
              [ "key" => "Facebook", "value" => "https://www.facebook.com/bill.thompson" ],
              [ "key" => "date_of_birth", "value" => "1990-01-01" ]
            ],
            ], $cmClient
        );
        $request = $mockClient->getRequests()[0];

        $this->assertEquals("PATCH", $request->getMethod());
        $uri = $request->getUri();
        $this->assertEquals("", $uri->getQuery());
        $this->assertEquals("/v1/contacts/".$uuid, $uri->getPath());

        $this->assertTrue($result instanceof Contact);
        $this->assertEquals("Bill", $result->first_name);
        $this->assertEquals("contact_external_id_002", $result->external_id);
    }

    public function testUpdateContactWithNullExternalId()
    {
        $stream = Psr7\Utils::streamFor(ContactTest::CONTACT_NULL_EXTERNAL_ID_JSON);
        list($cmClient, $mockClient) = $this->getMockClient(0, [200], $stream);

        $uuid = "con_00000000-0000-0000-0000-000000000000";

        $result = Contact::update(
            ["contact_uuid" => $uuid],
            ["external_id" => null],
            $cmClient
        );

        $this->assertTrue($result instanceof Contact);
        $this->assertNull($result->external_id);
    }

    public function testDeleteContact()
    {
        $stream = Psr7\Utils::streamFor("{}");
        list($cmClient, $mockClient) = $this->getMockClient(0, [200], $stream);

        $uuid = "con_00000000-0000-0000-0000-000000000000";

        $result = (new Contact(["uuid" => $uuid], $cmClient))->destroy();
        $request = $mockClient->getRequests()[0];

        $this->assertEquals("DELETE", $request->getMethod());
        $uri = $request->getUri();
        $this->assertEquals("", $uri->getQuery());
        $this->assertEquals("/v1/contacts/".$uuid, $uri->getPath());

        $this->assertEquals("{}", $result);
    }

    public function testMergeContacts()
    {
        $stream = Psr7\Utils::streamFor(ContactTest::CONTACT_JSON);
        list($cmClient, $mockClient) = $this->getMockClient(0, [200], $stream);

        $into_contact_uuid = "con_00000000-0000-0000-0000-000000000000";
        $from_contact_uuid = "con_00000000-0000-0000-0000-000000000001";

        $result = Contact::merge($into_contact_uuid, $from_contact_uuid, $cmClient);
        $request = $mockClient->getRequests()[0];

        $this->assertEquals("POST", $request->getMethod());
        $uri = $request->getUri();
        $this->assertEquals("", $uri->getQuery());
        $this->assertEquals("/v1/contacts/".$into_contact_uuid."/merge/".$from_contact_uuid, $uri->getPath());

        $this->assertTrue($result instanceof Contact);
    }

    public function testListContactsWithFilters()
    {
        $stream = Psr7\Utils::streamFor(ContactTest::LIST_CONTACTS_JSON);
        list($cmClient, $mockClient) = $this->getMockClient(0, [200], $stream);

        Contact::all(
            [
            "customer_uuid" => "cus_00000000-0000-0000-0000-000000000000",
            "data_source_uuid" => "ds_00000000-0000-0000-0000-000000000000",
            "email" => "adam@smith.com",
            "customer_external_id" => "123",
            "external_id" => "contact_001",
            ], $cmClient
        );
        $request = $mockClient->getRequests()[0];

        $this->assertEquals("/v1/contacts", $request->getUri()->getPath());
        parse_str($request->getUri()->getQuery(), $query);
        $this->assertEquals("cus_00000000-0000-0000-0000-000000000000", $query["customer_uuid"]);
        $this->assertEquals("ds_00000000-0000-0000-0000-000000000000", $query["data_source_uuid"]);
        $this->assertEquals("adam@smith.com", $query["email"]);
        $this->assertEquals("123", $query["customer_external_id"]);
        $this->assertEquals("contact_001", $query["external_id"]);
    }

    public function testCreateStandaloneContact()
    {
        $stream = Psr7\Utils::streamFor(ContactTest::STANDALONE_CONTACT_JSON);
        list($cmClient, $mockClient) = $this->getMockClient(0, [200], $stream);

        $result = Contact::create(
            [
            "first_name" => "Adam",
            "last_name" => "Smith",
            "email" => "adam@example.com",
            "last_active_at" => "2025-01-01T00:00:00Z",
            ], $cmClient
        );
        $request = $mockClient->getRequests()[0];

        $this->assertEquals("POST", $request->getMethod());
        $this->assertEquals("/v1/contacts", $request->getUri()->getPath());
        $body = json_decode((string) $request->getBody(), true);
        $this->assertEquals(
            [
            "first_name" => "Adam",
            "last_name" => "Smith",
            "email" => "adam@example.com",
            "last_active_at" => "2025-01-01T00:00:00Z",
            ], $body
        );

        $this->assertTrue($result instanceof Contact);
        $this->assertEquals("con_00000000-0000-0000-0000-000000000002", $result->uuid);
        $this->assertNull($result->customer_uuid);
        $this->assertNull($result->data_source_uuid);
        $this->assertEquals("2025-01-01T00:00:00.000Z", $result->last_seen);
    }

    public function testUpdateContactLastActiveAt()
    {
        $stream = Psr7\Utils::streamFor(ContactTest::STANDALONE_CONTACT_JSON);
        list($cmClient, $mockClient) = $this->getMockClient(0, [200], $stream);

        $uuid = "con_00000000-0000-0000-0000-000000000002";

        $result = Contact::update(
            ["contact_uuid" => $uuid],
            ["last_active_at" => "2025-01-01T00:00:00Z"],
            $cmClient
        );
        $request = $mockClient->getRequests()[0];

        $this->assertEquals("PATCH", $request->getMethod());
        $this->assertEquals("/v1/contacts/".$uuid, $request->getUri()->getPath());
        $this->assertEquals('{"last_active_at":"2025-01-01T00:00:00Z"}', (string) $request->getBody());

        $this->assertTrue($result instanceof Contact);
        $this->assertEquals("2025-01-01T00:00:00.000Z", $result->last_seen);
    }

    public function testListTasksForContact()
    {
        $stream = Psr7\Utils::streamFor(ContactTest::LIST_TASKS_JSON);
        list($cmClient, $mockClient) = $this->getMockClient(0, [200], $stream);

        $uuid = "con_00000000-0000-0000-0000-000000000000";

        $result = (new Contact(["uuid" => $uuid], $cmClient))->tasks(["cursor" => "cursor=="]);
        $request = $mockClient->getRequests()[0];

        $this->assertEquals("GET", $request->getMethod());
        $uri = $request->getUri();
        $this->assertEquals("/v1/tasks", $uri->getPath());
        $this->assertEquals("cursor=cursor%3D%3D&contact_uuid=".$uuid, $uri->getQuery());

        $this->assertTrue($result[0] instanceof Task);
        $this->assertEquals("00000000-0000-0000-0000-000000000002", $result[0]->uuid);
        $this->assertEquals("contact", $result[0]->associated_object);
        $this->assertEquals("cursor==", $result->cursor);
        $this->assertEquals(true, $result->has_more);
    }

    public function testCreateTaskForContact()
    {
        $stream = Psr7\Utils::streamFor(ContactTest::TASK_JSON);
        list($cmClient, $mockClient) = $this->getMockClient(0, [200], $stream);

        $uuid = "con_00000000-0000-0000-0000-000000000000";

        $result = (new Contact(["uuid" => $uuid], $cmClient))->createTask(
            [
            "task_details" => "Call the contact back.",
            "assignee" => "customer@example.com",
            "due_date" => "2025-04-30T00:00:00Z",
            ]
        );
        $request = $mockClient->getRequests()[0];

        $this->assertEquals("POST", $request->getMethod());
        $this->assertEquals("/v1/tasks", $request->getUri()->getPath());
        $this->assertEquals(
            '{"task_details":"Call the contact back.","assignee":"customer@example.com","due_date":"2025-04-30T00:00:00Z","associated_object_identifier":{"associated_object":"contact","method":"uuid","value":"'.$uuid.'"}}',
            (string) $request->getBody()
        );

        $this->assertTrue($result instanceof Task);
        $this->assertNull($result->customer_uuid);
        $this->assertEquals($uuid, $result->associated_object_uuid);
    }

    public function testCreateTaskForContactRespectsCallerCustomerUuid()
    {
        $stream = Psr7\Utils::streamFor(ContactTest::TASK_JSON);
        list($cmClient, $mockClient) = $this->getMockClient(0, [200], $stream);

        $uuid = "con_00000000-0000-0000-0000-000000000000";

        (new Contact(["uuid" => $uuid], $cmClient))->createTask(
            [
            "customer_uuid" => "cus_00000000-0000-0000-0000-000000000000",
            "task_details" => "Call the contact back.",
            "assignee" => "customer@example.com",
            "due_date" => "2025-04-30T00:00:00Z",
            ]
        );
        $request = $mockClient->getRequests()[0];

        $this->assertEquals(
            '{"customer_uuid":"cus_00000000-0000-0000-0000-000000000000","task_details":"Call the contact back.","assignee":"customer@example.com","due_date":"2025-04-30T00:00:00Z"}',
            (string) $request->getBody()
        );
    }

    public function testListEntityNotesForContact()
    {
        $stream = Psr7\Utils::streamFor(ContactTest::LIST_ENTITY_NOTES_JSON);
        list($cmClient, $mockClient) = $this->getMockClient(0, [200], $stream);

        $uuid = "con_00000000-0000-0000-0000-000000000000";

        $result = (new Contact(["uuid" => $uuid], $cmClient))->entityNotes(["type" => "note"]);
        $request = $mockClient->getRequests()[0];

        $this->assertEquals("GET", $request->getMethod());
        $uri = $request->getUri();
        $this->assertEquals("/v1/notes", $uri->getPath());
        $this->assertEquals("type=note&contact_uuid=".$uuid, $uri->getQuery());

        $this->assertTrue($result[0] instanceof EntityNote);
        $this->assertNull($result[0]->customer_uuid);
        $this->assertEquals($uuid, $result[0]->associated_object_uuid);
        $this->assertEquals("cursor==", $result->cursor);
        $this->assertEquals(true, $result->has_more);
    }

    public function testCreateEntityNoteForContact()
    {
        $stream = Psr7\Utils::streamFor(ContactTest::ENTITY_NOTE_JSON);
        list($cmClient, $mockClient) = $this->getMockClient(0, [200], $stream);

        $uuid = "con_00000000-0000-0000-0000-000000000000";

        $result = (new Contact(["uuid" => $uuid], $cmClient))->createEntityNote(
            [
            "type" => "note",
            "text" => "This is a contact note",
            ]
        );
        $request = $mockClient->getRequests()[0];

        $this->assertEquals("POST", $request->getMethod());
        $this->assertEquals("/v1/notes", $request->getUri()->getPath());
        $this->assertEquals(
            '{"type":"note","text":"This is a contact note","associated_object_identifier":{"associated_object":"contact","method":"uuid","value":"'.$uuid.'"}}',
            (string) $request->getBody()
        );

        $this->assertTrue($result instanceof EntityNote);
        $this->assertEquals("note_00000000-0000-0000-0000-000000000001", $result->uuid);
        $this->assertNull($result->customer_uuid);
        $this->assertEquals("contact", $result->associated_object);
        $this->assertEquals($uuid, $result->associated_object_uuid);
    }
}
