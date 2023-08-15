<?php
namespace ChartMogul\Tests;

use ChartMogul\Http\Client;
use ChartMogul\Contact;
use ChartMogul\Resource\CollectionWithCursor;
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

    public function testRetrieveContact()
    {
        $stream = Psr7\stream_for(ContactTest::CONTACT_JSON);
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
    }

    public function testCreateContact()
    {
        $stream = Psr7\stream_for(ContactTest::CONTACT_JSON);
        list($cmClient, $mockClient) = $this->getMockClient(0, [200], $stream);

        $result = Contact::create([
            "customer_uuid" => "cus_00000000-0000-0000-0000-000000000000",
            "data_source_uuid" => "ds_00000000-0000-0000-0000-000000000000",
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
        ], $cmClient);
        $request = $mockClient->getRequests()[0];

        $this->assertEquals("POST", $request->getMethod());
        $uri = $request->getUri();
        $this->assertEquals("", $uri->getQuery());
        $this->assertEquals("/v1/contacts", $uri->getPath());

        $this->assertTrue($result instanceof Contact);
        $this->assertEquals("Adam", $result->first_name);
    }

    public function testListContacts()
    {
        $stream = Psr7\stream_for(ContactTest::LIST_CONTACTS_JSON);
        list($cmClient, $mockClient) = $this->getMockClient(0, [200], $stream);

        $result = Contact::all([], $cmClient);
        $request = $mockClient->getRequests()[0];

        $this->assertEquals("GET", $request->getMethod());
        $uri = $request->getUri();
        $this->assertEquals("", $uri->getQuery());
        $this->assertEquals("/v1/contacts", $uri->getPath());

        $this->assertTrue($result instanceof CollectionWithCursor);
        $this->assertTrue($result[0] instanceof Contact);
        $this->assertEquals("cursor==", $result->cursor);
        $this->assertEquals(true, $result->has_more);
    }

    public function testUpdateContact()
    {
        $stream = Psr7\stream_for(ContactTest::UPDATED_CONTACT_JSON);
        list($cmClient, $mockClient) = $this->getMockClient(0, [200], $stream);

        $uuid = "con_00000000-0000-0000-0000-000000000000";

        $result = Contact::update([
            "contact_uuid" => $uuid,
            ], [
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
        ], $cmClient);
        $request = $mockClient->getRequests()[0];

        $this->assertEquals("PATCH", $request->getMethod());
        $uri = $request->getUri();
        $this->assertEquals("", $uri->getQuery());
        $this->assertEquals("/v1/contacts/".$uuid, $uri->getPath());

        $this->assertTrue($result instanceof Contact);
        $this->assertEquals("Bill", $result->first_name);
    }

    public function testDeleteContact()
    {
        $stream = Psr7\stream_for("{}");
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
        $stream = Psr7\stream_for(ContactTest::CONTACT_JSON);
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
}
