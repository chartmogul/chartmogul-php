<?php

namespace ChartMogul;

use ChartMogul\Resource\AbstractResource;
use ChartMogul\Resource\Collection;
use ChartMogul\Resource\CollectionWithCursor;
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
 * @property-read array $attributes
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
    public const RESOURCE_NAME = 'Customer';
    /**
     * @ignore
     */
    public const RESOURCE_PATH = '/v1/customers';
    public const RESOURCE_ID = 'customer_uuid';
    public const ROOT_KEY = 'entries';

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

    private $subscriptions;
    private $invoices;

    /**
     * Get Customer Tags
     *
     * @return array
     */
    public function tags()
    {
        return $this->attributes['tags'];
    }

    /**
     * Get Customer Custom Attributes
     *
     * @return array
     */
    public function customAttributes()
    {
        return $this->attributes['custom'];
    }

    /**
     * Find a Customer by External ID. Returns only first result!
     *
     * @param  string|array         $externalId
     * @param  ClientInterface|null $client
     * @return Customer|null
     */
    public static function findByExternalId($externalId, ClientInterface $client = null)
    {
        if (gettype($externalId) == 'string') {
            $externalId = ['external_id' => $externalId];
        }

        $response = self::all($externalId, $client);

        if ($response instanceof Collection
            && !$response->isEmpty()
        ) {
            return $response->first();
        }

        return null;
    }

    /**
     * Search for Customers
     *
     * @param  string               $email
     * @param  ClientInterface|null $client
     * @return Collection|static
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
     *
     * @param  array                $from
     * @param  array                $into
     * @param  ClientInterface|null $client
     * @return bool
     */
    public static function merge($from, $into, ClientInterface $client = null)
    {
        (new static([], $client))
            ->getClient()
            ->setResourcekey(static::class)
            ->send(
                '/v1/customers/merges',
                'POST',
                [
                'from' => $from,
                'into' => $into
                ]
            );
        return true;
    }

    /**
     * Connect Subscriptions
     *
     * @param  string               $customerUUID
     * @param  array                $data
     * @param  ClientInterface|null $client
     * @return bool
     */
    public static function connectSubscriptions($customerUUID, array $data = [], ClientInterface $client = null)
    {
        (new static([], $client))
            ->getClient()
            ->setResourcekey(static::class)
            ->send('/v1/customers/'.$customerUUID.'/connect_subscriptions', 'POST', $data);
        return true;
    }


    /**
     * Add tags to a customer
     *
     * @param  mixed $tags,...
     * @return array
     */
    public function addTags($tags)
    {
        $result = $this->getClient()
            ->send(
                '/v1/customers/'.$this->uuid.'/attributes/tags',
                'POST',
                [
                'tags' => func_get_args()
                ]
            );

        $this->attributes['tags'] = $result['tags'];
        return $result['tags'];
    }


    /**
     * Remove Tags from a Customer
     *
     * @param  mixed $tags,...
     * @return array
     */
    public function removeTags($tags)
    {
        $result = $this->getClient()
            ->send(
                '/v1/customers/'.$this->uuid.'/attributes/tags',
                'DELETE',
                [
                'tags' => func_get_args()
                ]
            );

        $this->attributes['tags'] = $result['tags'];
        return $result['tags'];
    }

    /**
     * Add Custom Attributes to a Customer
     *
     * @param  mixed $custom,...
     * @return array
     */
    public function addCustomAttributes($custom)
    {
        $result = $this->getClient()
            ->send(
                '/v1/customers/'.$this->uuid.'/attributes/custom',
                'POST',
                [
                'custom' => func_get_args()
                ]
            );

        $this->attributes['custom'] = $result['custom'];
        return $result['custom'];
    }


    /**
     * Remove Custom Attributes from a Customer
     *
     * @param  mixed $custom,...
     * @return array
     */
    public function removeCustomAttributes($custom)
    {
        $result = $this->getClient()
            ->send(
                '/v1/customers/'.$this->uuid.'/attributes/custom',
                'DELETE',
                [
                'custom' => func_get_args()
                ]
            );

        $this->attributes['custom'] = $result['custom'];
        return $result['custom'];
    }

    /**
     * Update Custom Attributes of a Customer
     *
     * @param  mixed $custom,...
     * @return array
     */
    public function updateCustomAttributes($custom)
    {
        $data = [];
        foreach (func_get_args() as $value) {
            $data = array_merge($data, $value);
        }
        $result = $this->getClient()
            ->send(
                '/v1/customers/'.$this->uuid.'/attributes/custom',
                'PUT',
                [
                'custom' => $data
                ]
            );

        $this->attributes['custom'] = $result['custom'];
        return $result['custom'];
    }

    /**
     * Find a Customer Subscriptions
     *
     * @param      array $options
     * @return     Collection | Customer
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
     *
     * @param      array $options
     * @return     Collection | Customer
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

    /**
     * Find all contacts in a customer
     *
     * @param  array $options
     * @return CollectionWithCursor
     */
    public function contacts(array $options = [])
    {
        $client = $this->getClient();
        $result = $client->send("/v1/customers/".$this->uuid."/contacts", "GET", $options);

        return Contact::fromArray($result, $client);
    }

    /**
     * Creates a contact from the customer.
     *
     * @param  array $data
     * @return Contact
     */
    public function createContact(array $data = [])
    {
        $client = $this->getClient();
        $result = $client->send("/v1/customers/".$this->uuid."/contacts", "POST", $data);

        return new Contact($result, $client);
    }

    /**
     * Find all customer notes in a customer
     *
     * @param  array $options
     * @return CollectionWithCursor
     */
    public function customer_notes(array $options = [])
    {
        $client = $this->getClient();
        $result = $client->send("/v1/customers/".$this->uuid."/notes", "GET", $options);

        return CustomerNote::fromArray($result, $client);
    }

    /**
     * Creates a customer note from the customer.
     *
     * @param  array $data
     * @return CustomerNote
     */
    public function createCustomerNote(array $data = [])
    {
        $client = $this->getClient();
        $result = $client->send("/v1/customers/".$this->uuid."/notes", "POST", $data);

        return new CustomerNote($result, $client);
    }

    /**
     * Retrieves a customer note from the customer.
     * @param  string $note_uuid
     * @return CustomerNote
     */
    public function retrieveCustomerNote(string $note_uuid)
    {
        $client = $this->getClient();
        $result = $client->send("/v1/customers/".$this->uuid."/notes/".$note_uuid, "GET");

        return new CustomerNote($result, $client);
    }

    /**
     * Updates a customer note from the customer.
     * @param  string $note_uuid
     * @param  array $data
     * @return CustomerNote
     */
    public function updateCustomerNote(string $note_uuid, array $data = [])
    {
        $client = $this->getClient();
        $result = $client->send("/v1/customers/".$this->uuid."/notes/".$note_uuid, "PATCH", $data);

        return new CustomerNote($result, $client);
    }

    /**
     * Deletes a customer note from the customer.
     * @param  string $note_uuid
     * @return CustomerNote
     */
    public function deleteCustomerNote(string $note_uuid)
    {
        $client = $this->getClient();
        $result = $client->send("/v1/customers/".$this->uuid."/notes/".$note_uuid, "DELETE");

        return new CustomerNote($result, $client);
    }
}
