<?php

namespace ChartMogul\Enrichment;

use ChartMogul\Resource\AbstractResource;
use ChartMogul\Http\ClientInterface;
use ChartMogul\Service\RequestService;

/**
 * @property-read string $id
 * @property-read string $uuid
 * @property-read string $external_id
 * @property-read string $name
 * @property-read string $email
 * @property-read string $status
 * @property-read string $customer_since
 * @property-read string $attributes
 * @property-read string $address
 * @property-read string $mrr
 * @property-read string $arr
 * @property-read string $billing_system_url
 * @property-read string $chartmogul_url
 * @property-read string $billing_system_type
 * @property-read string $currency
 * @property-read string $currency_sign

 */
class Customer extends AbstractResource
{
    const RESOURCE_NAME = 'Customer';
    const RESOURCE_PATH = '/v1/customers/:customer_uuid';

    protected $id;
    protected $uuid;
    protected $external_id;
    protected $name;
    protected $email;
    protected $status;
    protected $customer_since;
    protected $attributes;
    protected $address;
    protected $mrr;
    protected $arr;
    protected $billing_system_url;
    protected $chartmogul_url;
    protected $billing_system_type;
    protected $currency;
    protected $currency_sign;

    /**
     * Get Customer Tages
     * @return array
     */
    public function tags()
    {
        return $this->attributes['tags'];
    }

    /**
     * Get Customer Custom Attributes
     * @return array
     */
    public function customAttributes()
    {
        return $this->attributes['custom'];
    }

    /**
     * Retrieve a Customer
     * @param  string               $customer_uuid
     * @param  ClientInterface|null $client
     * @return Customer
     */
    public static function retrieve($customer_uuid, ClientInterface $client = null)
    {

        return (new RequestService($client))
            ->setResourceClass(static::class)
            ->all([
                'customer_uuid' => $customer_uuid
            ]);
    }

    /**
     * List all Customers
     * @param  array                $data
     * @param  ClientInterface|null $client
     * @return Customers
     */
    public static function all($data = [], ClientInterface $client = null)
    {
        return Customers::all($data, $client);
    }

    /**
     * Search for Customers
     * @param  string                $email
     * @param  ClientInterface|null $client
     * @return Customers
     */
    public static function search($email, ClientInterface $client = null)
    {
        return Customers::search($email, $client);
    }

    /**
     * Merge Customers
     * @param  array               $from
     * @param  array               $into
     * @param  ClientInterface|null $client
     * @return bool
     */
    public static function merge($from, $into, ClientInterface $client = null)
    {
        $response = (new static([], $client))
            ->getClient()
            ->setResourcekey(static::class)
            ->send('/v1/customers/merges', 'POST', [
                'from' => $from,
                'into' => $into
            ]);
        return true;
    }
}
