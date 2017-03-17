<?php

namespace ChartMogul;

use ChartMogul\Resource\AbstractResource;
use ChartMogul\Http\ClientInterface;
use ChartMogul\Service\UpdateTrait;
use ChartMogul\Service\CreateTrait;
use ChartMogul\Service\AllTrait;
use ChartMogul\Service\DestroyTrait;
use ChartMogul\Service\GetTrait;

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

    use CreateTrait;
    use AllTrait;
    use GetTrait;
    use DestroyTrait;
    use UpdateTrait;

    /**
     * @ignore
     */
    const RESOURCE_NAME = 'Customer';
    /**
     * @ignore
     */
    const RESOURCE_PATH = '/v1/customers';
    const RESOURCE_ID = 'customer_uuid';
    const ROOT_KEY = 'entries';

    protected $id;
    protected $uuid;
    protected $external_id;
    protected $name;
    protected $email;
    protected $company;
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

    // PATCH = Update a customer
    protected $data_source_uuid;
    protected $data_source_uuids;
    protected $external_ids;
    protected $city;
    protected $country;
    protected $state;
    protected $zip;
    protected $lead_created_at;
    protected $free_trial_started_at;

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
     * Find a Customer by External ID. Returns only first result!
     * @param string $externalId
     * @return Customer
     */
    public static function findByExternalId($externalId)
    {
        if (gettype($externalId) == 'string') {
            $externalId = ['external_id' => $externalId];
        }

        $response = static::all($externalId);

        if (is_null($response)) {
          return null;
        } else {
          return $response->first();
        }
    }

    /**
     * Search for Customers
     * @param  string                $email
     * @param  ClientInterface|null $client
     * @return Customers
     */
    public static function search($email, ClientInterface $client = null)
    {

        $response = (new static([], $client))
            ->getClient()
            ->setResourceKey(static::RESOURCE_NAME)
            ->send('/v1/customers/search', 'GET', ['email' => $email]);

        return static::fromArray($response, $client);
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
        (new static([], $client))
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
        $result = $this->getClient()
            ->send('/v1/customers/'.$this->uuid.'/attributes/tags', 'POST', [
                'tags' => func_get_args()
            ]);

        $this->attributes['tags'] = $result['tags'];
        return $result['tags'];
    }


    /**
     * Remove Tags from a Customer
     * @param mixed $tags,...
     * @return array
     */
    public function removeTags($tags)
    {
        $result = $this->getClient()
            ->send('/v1/customers/'.$this->uuid.'/attributes/tags', 'DELETE', [
                'tags' => func_get_args()
            ]);

        $this->attributes['tags'] = $result['tags'];
        return $result['tags'];
    }

    /**
     * Add Custom Attributes to a Customer
     * @param mixed $custom,...
     * @return array
     */
    public function addCustomAttributes($custom)
    {
        $result = $this->getClient()
            ->send('/v1/customers/'.$this->uuid.'/attributes/custom', 'POST', [
                'custom' => func_get_args()
            ]);

        $this->attributes['custom'] = $result['custom'];
        return $result['custom'];
    }


    /**
     * Remove Custom Attributes from a Customer
     * @param mixed $custom,...
     * @return array
     */
    public function removeCustomAttributes($custom)
    {
        $result = $this->getClient()
            ->send('/v1/customers/'.$this->uuid.'/attributes/custom', 'DELETE', [
                'custom' => func_get_args()
            ]);

        $this->attributes['custom'] = $result['custom'];
        return $result['custom'];
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
        $result = $this->getClient()
            ->send('/v1/customers/'.$this->uuid.'/attributes/custom', 'PUT', [
                'custom' => $data
            ]);

        $this->attributes['custom'] = $result['custom'];
        return $result['custom'];
    }

    /**
     * Find a Customer Subscriptions
     * @param  array  $options
     * @return \Doctrine\Common\Collections\ArrayCollection | Customer
     * @deprecated Use Import\Subscription.
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
     * Find customer's invoices
     * @param  array  $options
     * @return \Doctrine\Common\Collections\ArrayCollection | Customer
     * @deprecated Use Import\CustomerInvoices.
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
