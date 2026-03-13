<?php
namespace ChartMogul\IntegrationTests;

use ChartMogul\Contact;
use VCR\VCR;

class ContactTest extends IntegrationTestCase
{
    public function testCreateContactWithExternalId()
    {
        VCR::insertCassette('ContactCreateWithExternalId.yaml');

        $result = Contact::create([
            'customer_uuid' => 'cus_00000000-0000-0000-0000-000000000000',
            'data_source_uuid' => 'ds_00000000-0000-0000-0000-000000000000',
            'external_id' => 'contact_external_id_001',
        ], $this->client);

        $this->assertInstanceOf(Contact::class, $result);
        $this->assertEquals('contact_external_id_001', $result->external_id);
    }

    public function testCreateContactWithNullExternalId()
    {
        VCR::insertCassette('ContactCreateWithNullExternalId.yaml');

        $result = Contact::create([
            'customer_uuid' => 'cus_00000000-0000-0000-0000-000000000000',
            'data_source_uuid' => 'ds_00000000-0000-0000-0000-000000000000',
            'external_id' => null,
        ], $this->client);

        $this->assertInstanceOf(Contact::class, $result);
        $this->assertNull($result->external_id);
    }

    public function testCreateContactWithoutExternalId()
    {
        VCR::insertCassette('ContactCreateWithoutExternalId.yaml');

        $result = Contact::create([
            'customer_uuid' => 'cus_00000000-0000-0000-0000-000000000000',
            'data_source_uuid' => 'ds_00000000-0000-0000-0000-000000000000',
        ], $this->client);

        $this->assertInstanceOf(Contact::class, $result);
        $this->assertNull($result->external_id);
    }
}
