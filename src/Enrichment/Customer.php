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
    /**
     * @ignore
     */
    const RESOURCE_NAME = 'Customer';
    /**
     * @ignore
     */
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
     * Get Customer Tags
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


    /**
     * Add tags to a customer
     * @param mixed $tags,...
     * @return  array
     */
    public function addTags($tags)
    {
        $tags = $this->getClient()
            ->send('/v1/customers/'.$this->uuid.'/attributes/tags', 'POST', [
                'tags' => func_get_args()
            ]);

        $this->attributes['tags'] = $tags;
        return $tags;
    }


    /**
     * Remove Tags from a Customer
     * @param mixed $tags,...
     * @return array
     */
    public function removeTags($tags)
    {
        $tags = $this->getClient()
            ->send('/v1/customers/'.$this->uuid.'/attributes/tags', 'DELETE', [
                'tags' => func_get_args()
            ]);

        $this->attributes['tags'] = $tags;
        return $tags;
    }

    /**
     * Add Custom Attributes to a Customer
     * @param mixed $custom,...
     * @return array
     */
    public function addCustomAttributes($custom)
    {
        $custom = $this->getClient()
            ->send('/v1/customers/'.$this->uuid.'/attributes/custom', 'POST', [
                'custom' => func_get_args()
            ]);

        $this->attributes['custom'] = $custom;
        return $custom;
    }


    /**
     * Remove Custom Attributes from a Customer
     * @param mixed $custom,...
     * @return array
     */
    public function removeCustomAttributes($custom)
    {
        $custom = $this->getClient()
            ->send('/v1/customers/'.$this->uuid.'/attributes/custom', 'DELETE', [
                'custom' => func_get_args()
            ]);

        $this->attributes['custom'] = $custom;
        return $custom;
    }

    /**
     * Update Custom Attributes of a Customer
     * @param mixed $custom,...
     * @return array
     */
    public function updateCustomAttributes($custom)
    {

        $data = [];
        foreach (func_get_args() as $value) {
            $data = array_merge($data, $value);
        }
        $custom = $this->getClient()
            ->send('/v1/customers/'.$this->uuid.'/attributes/custom', 'PUT', [
                'custom' => $data
            ]);

        $this->attributes['custom'] = $custom;
        return $custom;
    }
}
