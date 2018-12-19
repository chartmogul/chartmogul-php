<?php

use ChartMogul\Http\Client;
use ChartMogul\Customer;
use ChartMogul\Exceptions\ChartMogulException;
use GuzzleHttp\Psr7;
use GuzzleHttp\Psr7\Response;

class CustomerTest extends \PHPUnit\Framework\TestCase
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
      "currency-sign": "$"
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
      "currency-sign": "$"
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
      "per_page":200,
      "page":1
    }';


    public function testRetrieveCustomer()
    {
        $stream = Psr7\stream_for(CustomerTest::RETRIEVE_CUSTOMER_JSON);
        $response = new Response(200, ['Content-Type' => 'application/json'], $stream);
        $mockClient = new \Http\Mock\Client();
        $mockClient->addResponse($response);

        $uuid = 'cus_de305d54-75b4-431b-adb2-eb6b9e546012';

        $cmClient = new Client(null, $mockClient);
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
        $response = new Response(200, ['Content-Type' => 'application/json'], $stream);
        $mockClient = new \Http\Mock\Client();
        $mockClient->addResponse($response);

        $cmClient = new Client(null, $mockClient);
        $result = Customer::create([
            "data_source_uuid" => "ds_fef05d54-47b4-431b-aed2-eb6b9e545430",
            "external_id" => "cus_0001",
            "name" => "Adam Smith",
            "email" => "adam@smith.com",
            "country" => "US",
            "city" => "New York",
            "lead_created_at" => "2016-10-01T00:00:00.000Z",
            "free_trial_started_at" => "2016-11-02T00:00:00.000Z"
        ], $cmClient);
        $request = $mockClient->getRequests()[0];

        $this->assertEquals("POST", $request->getMethod());
        $uri = $request->getUri();
        $this->assertEquals("", $uri->getQuery());
        $this->assertEquals("/v1/customers", $uri->getPath());

        $this->assertTrue($result instanceof Customer);
    }
    public function testSearchCustomer(){

        $stream = Psr7\stream_for(CustomerTest::SEARCH_CUSTOMER_JSON);
        $response = new Response(200, ['Content-Type' => 'application/json'], $stream);
        $mockClient = new \Http\Mock\Client();
        $mockClient->addResponse($response);


        $email = "bob@examplecompany.com";
        $cmClient = new Client(null, $mockClient);
        $result = Customer::search($email, $cmClient);
        $request = $mockClient->getRequests()[0];

        $this->assertEquals("GET", $request->getMethod());
        $uri = $request->getUri();
        $this->assertEquals("email=".urlencode($email), $uri->getQuery());
        $this->assertEquals("/v1/customers/search", $uri->getPath());

        $this->assertTrue($result[0] instanceof Customer);
        $this->assertEquals($result->has_more, false);
        $this->assertEquals($result->page, 1);
        $this->assertEquals($result->per_page, 200);
    }
    public function testConnectSubscriptions()
    {
        $stream = Psr7\stream_for('{}');
        $response = new Response(202, ['Content-Type' => 'application/json'], $stream);
        $mockClient = new \Http\Mock\Client();
        $mockClient->addResponse($response);

        $cmClient = new Client(null, $mockClient);
        $result = Customer::connectSubscriptions("cus_5915ee5a-babd-406b-b8ce-d207133fb4cb", [
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
        ], $cmClient);
        $request = $mockClient->getRequests()[0];

        $this->assertEquals("POST", $request->getMethod());
        $uri = $request->getUri();
        $this->assertEquals("", $uri->getQuery());
        $this->assertEquals("/v1/customers/cus_5915ee5a-babd-406b-b8ce-d207133fb4cb/connect_subscriptions", $uri->getPath());

        $this->assertEquals($result, true);
    }
}
