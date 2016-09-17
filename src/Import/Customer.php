<?php

namespace ChartMogul\Import;

use ChartMogul\Resource\AbstractResource;

class Customer extends AbstractResource
{

    use \ChartMogul\Service\CreateTrait;
    use \ChartMogul\Service\AllTrait;
    use \ChartMogul\Service\DestroyTrait;

    const RESOURCE_PATH = '/v1/import/customers';
    const RESOURCE_NAME = 'Customer';
    const ROOT_KEY = 'customers';

    protected $uuid;

    public $external_id;
    public $name;
    public $email;
    public $company;
    public $country;
    public $state;
    public $city;
    public $zip;
    public $data_source_uuid;

    /**
     * @param string $externalId
     * @return self
     */
    public static function findByExternalId($externalId)
    {
        return static::all(
            [
            'external_id' => $externalId
            ]
        )->first();
    }

    public function subscriptions(array $options = [])
    {
        if (!isset($this->subscriptions)) {
            $options['customer_uuid'] = $this->uuid;
            $this->invoices = Subscription::all($options);
        }
        return $this->subscriptions;
    }

    public function invoices(array $options = [])
    {
        if (!isset($this->invoices)) {
            $options['customer_uuid'] = $this->uuid;
            $this->invoices = CustomerInvoices::all($options)->invoices;
        }
        return $this->invoices;
    }
}
