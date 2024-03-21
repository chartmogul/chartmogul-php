<?php
namespace ChartMogul\Tests;

use ChartMogul\Http\Client;
use ChartMogul\Customer;
use ChartMogul\Contact;
use ChartMogul\CustomerNote;
use ChartMogul\Resource\Collection;
use ChartMogul\Exceptions\ChartMogulException;
use ChartMogul\Opportunity;
use GuzzleHttp\Psr7;
use GuzzleHttp\Psr7\Response;

class CustomerTest extends TestCase
{
    const CREATE_CUSTOMER_JSON= '{
      "id": 74596,
      "uuid": "cus_f466e33d-ff2b-4a11-8f85-417eb02157a7",
      "external_id": "cus_0001",
      "name": "Adam Smith",
      "email": "adam@smith.com",
      "status": "Lead",
      "customer-since": null,
      "attributes": {
        "custom": {
          "channel": "Facebook",
          "age": 18
        },
        "clearbit": {},
        "stripe": {},
        "tags": [
          "important",
          "Prio1"
        ]
      },
      "data_source_uuid": "ds_fef05d54-47b4-431b-aed2-eb6b9e545430",
      "data_source_uuids": [
        "ds_fef05d54-47b4-431b-aed2-eb6b9e545430"
      ],
      "external_ids": [
        "cus_0001"
      ],
      "company": "",
      "country": "US",
      "state": null,
      "city": "New York",
      "zip": null,
      "lead_created_at": "2015-10-14T00:00:00Z",
      "free_trial_started_at": "2015-11-01T00:00:00Z",
      "address": {
        "country": "United States",
        "state": null,
        "city": "New York",
        "address_zip": null
      },
      "mrr": 0,
      "arr": 0,
      "billing-system-url": null,
      "chartmogul-url": "https://app.chartmogul.com/#customers/74596-Adam_Smith",
      "billing-system-type": "Import API",
      "currency": "USD",
      "currency-sign": "$",
      "website_url": "http://www.adamsmith.com"
    }';

    const RETRIEVE_CUSTOMER_JSON = '{
      "id": 25647,
      "uuid": "cus_de305d54-75b4-431b-adb2-eb6b9e546012",
      "external_id": "34916129",
      "name": "Example Company",
      "email": "bob@examplecompany.com",
      "status": "Active",
      "customer-since": "2015-06-09T13:16:00-04:00",
      "attributes": {
        "tags": ["engage", "unit loss", "discountable"],
        "stripe": {
          "uid": 7,
          "coupon": true
        },
        "clearbit": {
          "id": "027b0d40-016c-40ea-8925-a076fa640992",
          "name": "Acme",
          "legalName": "Acme Inc.",
          "domain": "acme.com",
          "url": "http://acme.com",
          "metrics": {
            "raised": 1502450000,
            "employees": 1000,
            "googleRank": 7,
            "alexaGlobalRank": 2319,
            "marketCap": null
          },
          "category": {
            "sector": "Information Technology",
            "industryGroup": "Software and Services",
            "industry": "Software",
            "subIndustry": "Application Software"
          }
        },
        "custom": {
          "CAC": 213,
          "utmCampaign": "social media 1",
          "convertedAt": "2015-09-08 00:00:00",
          "pro": false,
          "salesRep": "Gabi"
        }
      },
      "address": {
        "address_zip": "0185128",
        "city": "Nowhereville",
        "state": "Alaska",
        "country": "US"
      },
      "data_source_uuid": "ds_fef05d54-47b4-431b-aed2-eb6b9e545430",
      "data_source_uuids": ["ds_fef05d54-47b4-431b-aed2-eb6b9e545430"],
      "external_ids": ["34916129"],
      "company": "",
      "country": "US",
      "state": "Alaska",
      "city": "Nowhereville",
      "zip": "0185128",
      "lead_created_at": null,
      "free_trial_started_at": null,
      "mrr": 3000,
      "arr": 36000,
      "billing-system-url": "https:\/\/dashboard.stripe.com\/customers\/cus_4Z2ZpyJFuQ0XMb",
      "chartmogul-url": "https:\/\/app.chartmogul.com\/#customers\/25647-Example_Company",
      "billing-system-type": "Stripe",
      "currency": "USD",
      "currency-sign": "$",
      "website_url": "http://www.adamsmith.com"
    }';

    const SEARCH_CUSTOMER_JSON = '{
      "entries":[
        {
          "id": 25647,
          "uuid": "cus_de305d54-75b4-431b-adb2-eb6b9e546012",
          "external_id": "34916129",
          "email": "bob@examplecompany.com",
          "name": "Example Company",
          "address": {
            "address_zip": "0185128",
            "city": "Nowhereville",
            "country": "US",
            "state": "Alaska"
          },
          "mrr": 3000,
          "arr": 36000,
          "status": "Active",
          "customer-since": "2015-06-09T13:16:00-04:00",
          "billing-system-url": "https:\/\/dashboard.stripe.com\/customers\/cus_4Z2ZpyJFuQ0XMb",
          "chartmogul-url": "https:\/\/app.chartmogul.com\/#customers\/25647-Example_Company",
          "billing-system-type": "Stripe",
          "currency": "USD",
          "currency-sign": "$"
        }
      ],
      "has_more":false,
      "cursor": "cursor=="
    }';

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

    const NOTE_JSON= '{
      "uuid": "note_00000000-0000-0000-0000-000000000000",
      "customer_uuid": "cus_00000000-0000-0000-0000-000000000000",
      "type": "note",
      "author": "John Doe (john@example.com)",
      "text": "This is a note",
      "created_at": "2015-06-09T13:16:00-04:00",
      "updated_at": "2015-06-09T13:16:00-04:00"
    }';

    const LIST_OPPORTUNITIES_JSON= '{
      "entries": [
        {
          "uuid": "00000000-0000-0000-0000-000000000000",
          "customer_uuid": "cus_00000000-0000-0000-0000-000000000000",
          "owner": "test1@example.org",
          "pipeline": "New business 1",
          "pipeline_stage": "Discovery",
          "estimated_close_date": "2023-12-22",
          "currency": "USD",
          "amount_in_cents": 100,
          "type": "recurring",
          "forecast_category": "pipeline",
          "win_likelihood": 3,
          "custom": {"from_campaign": "true"},
          "created_at": "2024-03-13T07:33:28.356Z",
          "updated_at": "2024-03-13T07:33:28.356Z"
        }
      ],
      "cursor": "cursor==",
      "has_more": true
    }';

    const OPPORTUNITY_JSON= '{
      "uuid": "00000000-0000-0000-0000-000000000000",
      "customer_uuid": "cus_00000000-0000-0000-0000-000000000000",
      "owner": "test1@example.org",
      "pipeline": "New business 1",
      "pipeline_stage": "Discovery",
      "estimated_close_date": "2023-12-22",
      "currency": "USD",
      "amount_in_cents": 100,
      "type": "recurring",
      "forecast_category": "pipeline",
      "win_likelihood": 3,
      "custom": {"from_campaign": "true"},
      "created_at": "2024-03-13T07:33:28.356Z",
      "updated_at": "2024-03-13T07:33:28.356Z"
    }';

    public function testRetrieveCustomer()
    {
        $stream = Psr7\stream_for(CustomerTest::RETRIEVE_CUSTOMER_JSON);
        list($cmClient, $mockClient) = $this->getMockClient(0, [200], $stream);

        $uuid = 'cus_de305d54-75b4-431b-adb2-eb6b9e546012';

        $result = Customer::retrieve($uuid, $cmClient);
        $request = $mockClient->getRequests()[0];

        $this->assertEquals("GET", $request->getMethod());
        $uri = $request->getUri();
        $this->assertEquals("", $uri->getQuery());
        $this->assertEquals("/v1/customers/".$uuid, $uri->getPath());

        $this->assertTrue($result instanceof Customer);
        $this->assertEquals($uuid, $result->uuid);
    }

    public function testCreateCustomer()
    {
        $stream = Psr7\stream_for(CustomerTest::CREATE_CUSTOMER_JSON);
        list($cmClient, $mockClient) = $this->getMockClient(0, [200], $stream);

        $result = Customer::create(
            [
            "data_source_uuid" => "ds_fef05d54-47b4-431b-aed2-eb6b9e545430",
            "external_id" => "cus_0001",
            "name" => "Adam Smith",
            "email" => "adam@smith.com",
            "country" => "US",
            "city" => "New York",
            "lead_created_at" => "2016-10-01T00:00:00.000Z",
            "free_trial_started_at" => "2016-11-02T00:00:00.000Z",
            "website_url" => "http://www.adamsmith.com"
            ], $cmClient
        );
        $request = $mockClient->getRequests()[0];

        $this->assertEquals("POST", $request->getMethod());
        $uri = $request->getUri();
        $this->assertEquals("", $uri->getQuery());
        $this->assertEquals("/v1/customers", $uri->getPath());

        $this->assertTrue($result instanceof Customer);
    }

    public function testSearchCustomer()
    {
        $stream = Psr7\stream_for(CustomerTest::SEARCH_CUSTOMER_JSON);
        list($cmClient, $mockClient) = $this->getMockClient(0, [200], $stream);

        $email = "bob@examplecompany.com";
        $result = Customer::search($email, $cmClient);
        $request = $mockClient->getRequests()[0];

        $this->assertEquals("GET", $request->getMethod());
        $uri = $request->getUri();
        $this->assertEquals("email=".urlencode($email), $uri->getQuery());
        $this->assertEquals("/v1/customers/search", $uri->getPath());

        $this->assertTrue($result instanceof Collection);
        $this->assertTrue($result[0] instanceof Customer);
        $this->assertEquals($result->has_more, false);
        $this->assertEquals($result->cursor, "cursor==");
    }

    public function testConnectSubscriptions()
    {
        $stream = Psr7\stream_for('{}');
        list($cmClient, $mockClient) = $this->getMockClient(0, [200], $stream);

        $result = Customer::connectSubscriptions(
            "cus_5915ee5a-babd-406b-b8ce-d207133fb4cb", [
            "subscriptions" => [
                [
                    "data_source_uuid" => "ds_ade45e52-47a4-231a-1ed2-eb6b9e541213",
                    "external_id" => "d1c0c885-add0-48db-8fa9-0bdf5017d6b0",
                ],
                [
                    "data_source_uuid" => "ds_ade45e52-47a4-231a-1ed2-eb6b9e541213",
                    "external_id" => "9db5f4a1-1695-44c0-8bd4-de7ce4d0f1d4",
                ],
            ]
            ], $cmClient
        );
        $request = $mockClient->getRequests()[0];

        $this->assertEquals("POST", $request->getMethod());
        $uri = $request->getUri();
        $this->assertEquals("", $uri->getQuery());
        $this->assertEquals("/v1/customers/cus_5915ee5a-babd-406b-b8ce-d207133fb4cb/connect_subscriptions", $uri->getPath());

        $this->assertEquals($result, true);
    }

    public function testFindByExternalId()
    {
        $stream = Psr7\stream_for(CustomerTest::SEARCH_CUSTOMER_JSON);
        list($cmClient, $mockClient) = $this->getMockClient(0, [200], $stream);

        $result = Customer::findByExternalId("34916129", $cmClient);
        $request = $mockClient->getRequests()[0];

        $this->assertEquals("GET", $request->getMethod());
        $uri = $request->getUri();
        $this->assertEquals("external_id=34916129", $uri->getQuery());
        $this->assertEquals("/v1/customers", $uri->getPath());
        $this->assertTrue($result instanceof Customer);
    }

    public function testListCustomersContacts()
    {
        $stream = Psr7\stream_for(CustomerTest::LIST_CONTACTS_JSON);
        list($cmClient, $mockClient) = $this->getMockClient(0, [200], $stream);

        $uuid = "cus_00000000-0000-0000-0000-000000000000";

        $result = (new Customer(["uuid" => $uuid], $cmClient))->contacts();
        $request = $mockClient->getRequests()[0];

        $this->assertEquals("GET", $request->getMethod());
        $uri = $request->getUri();
        $this->assertEquals("/v1/customers/".$uuid."/contacts", $uri->getPath());

        $this->assertTrue($result[0] instanceof Contact);
        $this->assertEquals("cursor==", $result->cursor);
        $this->assertEquals(true, $result->has_more);
    }

    public function testCreateCustomersContact()
    {
        $stream = Psr7\stream_for(CustomerTest::CONTACT_JSON);
        list($cmClient, $mockClient) = $this->getMockClient(0, [200], $stream);

        $uuid = "cus_00000000-0000-0000-0000-000000000000";

        $result = (new Customer(["uuid" => $uuid], $cmClient))->createContact(
            [
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
            ]
        );
        $request = $mockClient->getRequests()[0];

        $this->assertEquals("POST", $request->getMethod());
        $uri = $request->getUri();
        $this->assertEquals("/v1/customers/".$uuid."/contacts", $uri->getPath());

        $this->assertTrue($result instanceof Contact);
        $this->assertEquals("con_00000000-0000-0000-0000-000000000000", $result->uuid);
    }

    public function testListCustomerNotes()
    {
        $stream = Psr7\stream_for(CustomerTest::LIST_NOTES_JSON);
        list($cmClient, $mockClient) = $this->getMockClient(0, [200], $stream);

        $uuid = "cus_00000000-0000-0000-0000-000000000000";

        $result = (new Customer(["uuid" => $uuid], $cmClient))->notes();
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
        $stream = Psr7\stream_for(CustomerTest::NOTE_JSON);
        list($cmClient, $mockClient) = $this->getMockClient(0, [200], $stream);

        $uuid = "cus_00000000-0000-0000-0000-000000000000";

        $result = (new Customer(["uuid" => $uuid], $cmClient))->createNote(
          [
            "type" => "note",
            "author_email" => "john@example.com",
            "text" => "This is a note",
          ]
        );
        $request = $mockClient->getRequests()[0];

        $this->assertEquals("POST", $request->getMethod());
        $uri = $request->getUri();
        $this->assertEquals("/v1/customer_notes", $uri->getPath());
        $requestBody = (string) $request->getBody();
        $this->assertEquals('{"type":"note","author_email":"john@example.com","text":"This is a note","customer_uuid":"cus_00000000-0000-0000-0000-000000000000"}', $requestBody);

        $this->assertTrue($result instanceof CustomerNote);
        $this->assertEquals("note_00000000-0000-0000-0000-000000000000", $result->uuid);
    }

    public function testListOpportunities()
    {
        $stream = Psr7\stream_for(CustomerTest::LIST_OPPORTUNITIES_JSON);
        list($cmClient, $mockClient) = $this->getMockClient(0, [200], $stream);

        $customer_uuid = "cus_00000000-0000-0000-0000-000000000000";

        $result = (new Customer(["uuid" => $customer_uuid], $cmClient))->opportunities();
        $request = $mockClient->getRequests()[0];

        $this->assertEquals("GET", $request->getMethod());
        $uri = $request->getUri();
        $this->assertEquals("/v1/opportunities", $uri->getPath());

        $this->assertTrue($result[0] instanceof Opportunity);
        $this->assertEquals("cursor==", $result->cursor);
        $this->assertEquals(true, $result->has_more);
    }

    public function testCreateOpportunity()
    {
        $stream = Psr7\stream_for(CustomerTest::OPPORTUNITY_JSON);
        list($cmClient, $mockClient) = $this->getMockClient(0, [200], $stream);

        $customer_uuid = "cus_00000000-0000-0000-0000-000000000000";

        $result = (new Customer(["uuid" => $customer_uuid], $cmClient))->createOpportunity(
          [
            "owner" => "test1@example.org",
            "pipeline" => "New business 1",
            "pipeline_stage" => "Discovery",
            "estimated_close_date" => "2023-12-22",
            "currency" => "USD",
            "amount_in_cents" => 100,
            "type" => "recurring",
            "forecast_category" => "pipeline",
            "win_likelihood" => 3,
            "custom" => [
              [ "key" => "from_campaign", "value" => true ],
            ]
          ]
        );
        $request = $mockClient->getRequests()[0];

        $this->assertEquals("POST", $request->getMethod());
        $uri = $request->getUri();
        $this->assertEquals("/v1/opportunities", $uri->getPath());
        $requestBody = (string) $request->getBody();
        $this->assertEquals('{"owner":"test1@example.org","pipeline":"New business 1","pipeline_stage":"Discovery","estimated_close_date":"2023-12-22","currency":"USD","amount_in_cents":100,"type":"recurring","forecast_category":"pipeline","win_likelihood":3,"custom":[{"key":"from_campaign","value":true}],"customer_uuid":"cus_00000000-0000-0000-0000-000000000000"}', $requestBody);

        $this->assertTrue($result instanceof Opportunity);
        $this->assertEquals("00000000-0000-0000-0000-000000000000", $result->uuid);
    }
}
