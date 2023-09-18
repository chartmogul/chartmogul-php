<?php
namespace ChartMogul\Tests;

use ChartMogul\Http\Client;
use ChartMogul\Invoice;
use ChartMogul\CustomerInvoices;
use ChartMogul\Exceptions\ChartMogulException;
use ChartMogul\Resource\CollectionWithCursor;
use GuzzleHttp\Psr7;
use GuzzleHttp\Psr7\Response;
use ChartMogul\Exceptions\NotFoundException;

class InvoiceTest extends TestCase
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
              "transaction_fees_currency": "EUR",
              "discount_description": "5 EUR",
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
              "transaction_fees_currency": "EUR",
              "discount_description": "2 EUR",
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
      "cursor": "MjAyMy0wNy0yO",
      "has_more": true
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
          "transaction_fees_currency": "EUR",
          "discount_description": "5 EUR",
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
          "transaction_fees_currency": "EUR",
          "discount_description": "2 EUR",
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

    public function testCreateInvoiceFailsOnValidation()
    {
      $stream = Psr7\stream_for('{invoices: [{errors: {"plan_id": "doesn\'t exist"}}]}');
      list($cmClient, $mockClient) = $this->getMockClient(0, [422], $stream);

      $this->expectException(\ChartMogul\Exceptions\SchemaInvalidException::class);
      $restult = CustomerInvoices::create([
      'customer_uuid' => 'some_id',
      'invoices' => [['mock' => 'invoice']]
      ], $cmClient);
    }

    public function testAllInvoices()
    {
        $stream = Psr7\stream_for(InvoiceTest::ALL_INVOICE_JSON);
        list($cmClient, $mockClient) = $this->getMockClient(0, [200], $stream);

        $query = ["cursor" => "MjAyMy0wNy0yO", "external_id" => "INV0001"];
        $result = Invoice::all($query, $cmClient);
        $request = $mockClient->getRequests()[0];

        $this->assertEquals("GET", $request->getMethod());
        $uri = $request->getUri();
        $this->assertEquals("cursor=MjAyMy0wNy0yO&external_id=INV0001", $uri->getQuery());
        $this->assertEquals("/v1/invoices", $uri->getPath());

        $this->assertTrue($result instanceof CollectionWithCursor);
        $this->assertEquals(1, sizeof($result));
        $this->assertTrue($result[0] instanceof Invoice);
        $this->assertEquals("cus_f466e33d-ff2b-4a11-8f85-417eb02157a7", $result[0]->customer_uuid);
        $this->assertEquals("MjAyMy0wNy0yO", $result->cursor);
        $this->assertEquals(true, $result->has_more);
    }

    public function testDestroyInvoice()
    {
        list($cmClient, $mockClient) = $this->getMockClient(0, [204]);

        $result = (new Invoice(["uuid" => "inv_123"], $cmClient))->destroy();
        $request = $mockClient->getRequests()[0];

        $this->assertEquals("DELETE", $request->getMethod());
        $uri = $request->getUri();
        $this->assertEquals("", $uri->getQuery());
        $this->assertEquals("/v1/invoices/inv_123", $uri->getPath());
    }

    public function testDestroyInvoiceNotFound()
    {
        list($cmClient, $mockClient) = $this->getMockClient(0, [404]);
        $this->expectException(NotFoundException::class);

        $result = (new Invoice(["uuid" => "inv_123"], $cmClient))->destroy();
    }

    public function testRetrieveInvoice()
    {
        $stream = Psr7\stream_for(InvoiceTest::RETRIEVE_INVOICE_JSON);
        list($cmClient, $mockClient) = $this->getMockClient(0, [200], $stream);

        $uuid = 'inv_565c73b2-85b9-49c9-a25e-2b7df6a677c9';

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
