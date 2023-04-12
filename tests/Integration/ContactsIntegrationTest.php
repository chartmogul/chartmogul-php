<?php
namespace ChartMogul\IntegrationTests;

use ChartMogul;
use VCR\VCR;

class ContactsIntegrationTest extends IntegrationTestCase
{
    public function testContacts()
    {
        VCR::insertCassette('ContactsIntegrationTest.yaml');
        
        $contact_params = [
          "customer_uuid" => "cus_80ec0c06-bef5-11ed-a88c-974c518fc467",
          "data_source_uuid" => "ds_80251880-bef5-11ed-a88b-db28feaa7eba",
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
        ];

        $new_contact = ChartMogul\Contact::create($contact_params, $this->client);
        $retrieved_contact = ChartMogul\Contact::retrieve($new_contact->uuid, $this->client);

        $this->assertEquals($new_contact->uuid, $retrieved_contact->uuid);

        $updated_contact = ChartMogul\Contact::update([
          "contact_uuid" => $retrieved_contact->uuid,
          ], [
          "first_name" => "Bill",
          "last_name" => "Thompson",
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
        ], $this->client);

        $this->assertEquals($updated_contact->uuid, $retrieved_contact->uuid);
        $this->assertNotEquals($updated_contact->email, $retrieved_contact->email);

        $empty_contact = ChartMogul\Contact::create([
          "customer_uuid" => "cus_80ec0c06-bef5-11ed-a88c-974c518fc467",
          "data_source_uuid" => "ds_80251880-bef5-11ed-a88b-db28feaa7eba"
        ], $this->client);
        $merged_contact = ChartMogul\Contact::merge($empty_contact->uuid, $updated_contact->uuid, $this->client);
        $this->assertEquals($merged_contact->email, $updated_contact->email);

        $merged_contact->destroy($this->client);

        $this->expectException(\ChartMogul\Exceptions\NotFoundException::class);
        $deleted_contact = ChartMogul\Contact::retrieve($merged_contact->uuid, $this->client);
    }
}
