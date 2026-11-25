<?php

namespace ChartMogul;

use ChartMogul\Http\ClientInterface;
use ChartMogul\Resource\AbstractResource;
use ChartMogul\Service\AllTrait;

/**
 * An excluded data source customer. The same customer_uuid can appear once per data source.
 * ExcludedCustomer::reinclude() returns only `customer_uuid` and `status`.
 *
 * @property-read string $customer_uuid
 * @property-read string $data_source_uuid
 * @property-read string $external_id
 * @property-read string $name
 * @property-read string $email
 * @property-read string $excluded_at
 * @property-read string $status `reimporting` or `manual_reimport_required`
 */
class ExcludedCustomer extends AbstractResource
{
    use AllTrait;

    /**
     * @ignore
     */
    public const RESOURCE_NAME = 'ExcludedCustomer';
    /**
     * @ignore
     */
    public const RESOURCE_PATH = '/v1/excluded_customers';
    public const RESOURCE_ID = 'customer_uuid';
    public const ROOT_KEY = 'entries';

    protected $customer_uuid;
    protected $data_source_uuid;
    protected $external_id;
    protected $name;
    protected $email;
    protected $excluded_at;
    protected $status;

    /**
     * Remove a customer from the exclude list so it can be imported again.
     *
     * @param  string               $customerUUID
     * @param  ClientInterface|null $client
     * @return ExcludedCustomer
     */
    public static function reinclude(string $customerUUID, ?ClientInterface $client = null)
    {
        $response = (new static([], $client))
            ->getClient()
            ->setResourceKey(static::RESOURCE_NAME)
            ->send(static::RESOURCE_PATH.'/'.$customerUUID, 'DELETE');

        return new static($response, $client);
    }
}
