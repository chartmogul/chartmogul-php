<?php

use ChartMogul\Http\Client;
use ChartMogul\Invoice;
use ChartMogul\Exceptions\ChartMogulException;
use GuzzleHttp\Psr7;
use GuzzleHttp\Psr7\Response;
use ChartMogul\Exceptions\NotFoundException;

class InvoiceTest extends \PHPUnit\Framework\TestCase
{
    const ALL_INVOICE_JSON = '{
      "invoices": [
        {
          "uuid": "inv_565c73b2-85b9-49c9-a25e-2b7df6a677c9",
          "customer_uuid": "cus_f466e33d-ff2b-4a11-8f85-417eb02157a7",
          "external_id": "INV0001",
          "date": "2015-11-01T00:00:00.000Z",
          "due_date": "2015-11-15T00:00:00.000Z",
          "currency": "USD",
          "line_items": [
            {
              "uuid": "li_d72e6843-5793-41d0-bfdf-0269514c9c56",
              "external_id": null,
              "type": "subscription",
              "subscription_uuid": "sub_e6bc5407-e258-4de0-bb43-61faaf062035",
              "plan_uuid": "pl_eed05d54-75b4-431b-adb2-eb6b9e543206",
              "prorated": false,
              "service_period_start": "2015-11-01T00:00:00.000Z",
              "service_period_end": "2015-12-01T00:00:00.000Z",
              "amount_in_cents": 5000,
              "quantity": 1,
              "discount_code": "PSO86",
              "discount_amount_in_cents": 1000,
              "tax_amount_in_cents": 900,
              "account_code": null
            },
            {
              "uuid": "li_0cc8c112-beac-416d-af11-f35744ca4e83",
              "external_id": null,
              "type": "one_time",
              "description": "Setup Fees",
              "amount_in_cents": 2500,
              "quantity": 1,
              "discount_code": "PSO86",
              "discount_amount_in_cents": 500,
              "tax_amount_in_cents": 450,
              "account_code": null
            }
          ],
          "transactions": [
            {
              "uuid": "tr_879d560a-1bec-41bb-986e-665e38a2f7bc",
              "external_id": null,
              "type": "payment",
              "date": "2015-11-05T00:14:23.000Z",
              "result": "successful"
            }
          ]
        }
      ],
      "current_page": 2,
      "total_pages": 3
    }';

    const RETRIEVE_INVOICE_JSON = '{
      "uuid": "inv_565c73b2-85b9-49c9-a25e-2b7df6a677c9",
      "external_id": "INV0001",
      "date": "2015-11-01T00:00:00.000Z",
      "due_date": "2015-11-15T00:00:00.000Z",
      "currency": "USD",
      "line_items": [
        {
          "uuid": "li_d72e6843-5793-41d0-bfdf-0269514c9c56",
          "external_id": null,
          "type": "subscription",
          "subscription_uuid": "sub_e6bc5407-e258-4de0-bb43-61faaf062035",
          "plan_uuid": "pl_eed05d54-75b4-431b-adb2-eb6b9e543206",
          "prorated": false,
          "service_period_start": "2015-11-01T00:00:00.000Z",
          "service_period_end": "2015-12-01T00:00:00.000Z",
          "amount_in_cents": 5000,
          "quantity": 1,
          "discount_code": "PSO86",
          "discount_amount_in_cents": 1000,
          "tax_amount_in_cents": 900,
          "account_code": null
        },
        {
          "uuid": "li_0cc8c112-beac-416d-af11-f35744ca4e83",
          "external_id": null,
          "type": "one_time",
          "description": "Setup Fees",
          "amount_in_cents": 2500,
          "quantity": 1,
          "discount_code": "PSO86",
          "discount_amount_in_cents": 500,
          "tax_amount_in_cents": 450,
          "account_code": null
        }
      ],
      "transactions": [
        {
          "uuid": "tr_879d560a-1bec-41bb-986e-665e38a2f7bc",
          "external_id": null,
          "type": "payment",
          "date": "2015-11-05T00:14:23.000Z",
          "result": "successful"
        }
      ]
    }';

    public function testCreateInvoice()
    {
      $stream = Psr7\stream_for('{invoices: [{errors: {"plan_id": "doesn\'t exist"}}]}');
      $response = new Response(422, ['Content-Type' => 'application/json'], $stream);
      $mockClient = new \Http\Mock\Client();
      $mockClient->addResponse($response);
      $cmClient = new Client(null, $mockClient);

      $this->expectException(\ChartMogul\Exceptions\SchemaInvalidException::class);
      $restult = ChartMogul\CustomerInvoices::create([
      'customer_uuid' => 'some_id',
      'invoices' => [['mock' => 'invoice']]
      ], $cmClient);
    }


    public function testAllInvoices()
    {
        $stream = Psr7\stream_for(InvoiceTest::ALL_INVOICE_JSON);
        $response = new Response(200, ['Content-Type' => 'application/json'], $stream);
        $mockClient = new \Http\Mock\Client();
        $mockClient->addResponse($response);

        $cmClient = new Client(null, $mockClient);
        $query = ["page" => 2, "external_id" => "INV0001"];
        $result = Invoice::all($query, $cmClient);
        $request = $mockClient->getRequests()[0];

        $this->assertEquals("GET", $request->getMethod());
        $uri = $request->getUri();
        $this->assertEquals("page=2&external_id=INV0001", $uri->getQuery());
        $this->assertEquals("/v1/invoices", $uri->getPath());

        $this->assertEquals(1, sizeof($result));
        $this->assertTrue($result[0] instanceof Invoice);
        $this->assertEquals("cus_f466e33d-ff2b-4a11-8f85-417eb02157a7", $result[0]->customer_uuid);
        $this->assertEquals(2, $result->current_page);
        $this->assertEquals(3, $result->total_pages);
    }

    public function testDestroyInvoice()
    {
        $response = new Response(204);
        $mockClient = new \Http\Mock\Client();
        $mockClient->addResponse($response);

        $cmClient = new Client(null, $mockClient);
        $result = (new Invoice(["uuid" => "inv_123"], $cmClient))->destroy();
        $request = $mockClient->getRequests()[0];

        $this->assertEquals("DELETE", $request->getMethod());
        $uri = $request->getUri();
        $this->assertEquals("", $uri->getQuery());
        $this->assertEquals("/v1/invoices/inv_123", $uri->getPath());
    }

    public function testDestroyInvoiceNotFound()
    {
        $this->expectException(NotFoundException::class);
        $response = new Response(404);
        $mockClient = new \Http\Mock\Client();
        $mockClient->addResponse($response);

        $cmClient = new Client(null, $mockClient);
        $result = (new Invoice(["uuid" => "inv_123"], $cmClient))->destroy();
    }

    public function testRetrieveInvoice()
    {
        $stream = Psr7\stream_for(InvoiceTest::RETRIEVE_INVOICE_JSON);
        $response = new Response(200, ['Content-Type' => 'application/json'], $stream);
        $mockClient = new \Http\Mock\Client();
        $mockClient->addResponse($response);

        $uuid = 'inv_565c73b2-85b9-49c9-a25e-2b7df6a677c9';

        $cmClient = new Client(null, $mockClient);
        $result = Invoice::retrieve($uuid, $cmClient);
        $request = $mockClient->getRequests()[0];

        $this->assertEquals("GET", $request->getMethod());
        $uri = $request->getUri();
        $this->assertEquals("", $uri->getQuery());
        $this->assertEquals("/v1/invoices/".$uuid, $uri->getPath());

        $this->assertTrue($result instanceof Invoice);
        $this->assertEquals($uuid, $result->uuid);
    }
}
