<?php

namespace ChartMogul\Import;

use ChartMogul\Resource\AbstractResource;

/**
 * @property-read string $uuid
 */
class Customer extends AbstractResource
{

    use \ChartMogul\Service\CreateTrait;
    use \ChartMogul\Service\AllTrait;
    use \ChartMogul\Service\DestroyTrait;

    /**
     * @ignore
     */
    const RESOURCE_PATH = '/v1/import/customers';
    /**
     * @ignore
     */
    const RESOURCE_NAME = 'Customer';
    /**
     * @ignore
     */
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
    public $lead_created_at;
    public $free_trial_started_at;

    /**
     * Find a Customer by External ID
     * @param string $externalId
     * @return Customer
     */
    public static function findByExternalId($externalId)
    {
        return static::all(
            [
                'external_id' => $externalId
            ]
        )->first();
    }

    /**
     * Find a Customer Subscriptions
     * @param  array  $options
     * @return \Doctrine\Common\Collections\ArrayCollection | Customer
     */
    public function subscriptions(array $options = [])
    {
        if (!isset($this->subscriptions)) {
            $options['customer_uuid'] = $this->uuid;
            $this->subscriptions = Subscription::all($options);
        }
        return $this->subscriptions;
    }

    /**
     * Find a Customer Invoices
     * @param  array  $options
     * @return \Doctrine\Common\Collections\ArrayCollection | Customer
     */
    public function invoices(array $options = [])
    {
        if (!isset($this->invoices)) {
            $options['customer_uuid'] = $this->uuid;
            $this->invoices = CustomerInvoices::all($options)->invoices;
        }
        return $this->invoices;
    }
}
