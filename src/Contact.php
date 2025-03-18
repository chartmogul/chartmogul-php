<?php

namespace ChartMogul;

use ChartMogul\Resource\AbstractResource;
use ChartMogul\Http\ClientInterface;
use ChartMogul\Service\AllTrait;
use ChartMogul\Service\UpdateTrait;
use ChartMogul\Service\CreateTrait;
use ChartMogul\Service\DestroyTrait;
use ChartMogul\Service\GetTrait;
use ChartMogul\Service\FromArrayTrait;

/**
 * @property-read string $uuid
 * @property-read string $customer_uuid
 * @property-read string $data_source_uuid
 * @property-read string $customer_external_id
 * @property-read string $first_name
 * @property-read string $last_name
 * @property-read integer $position
 * @property-read string $email
 * @property-read string $title
 * @property-read string $notes
 * @property-read string $phone
 * @property-read string $linked_in
 * @property-read string $twitter
 * @property-read string $custom
 */
class Contact extends AbstractResource
{
    use CreateTrait;
    use AllTrait;
    use GetTrait;
    use DestroyTrait;
    use UpdateTrait;
    use FromArrayTrait;

    /**
     * @ignore
     */
    public const RESOURCE_NAME = 'Contact';
    /**
     * @ignore
     */
    public const RESOURCE_PATH = '/v1/contacts';
    public const RESOURCE_ID = 'contact_uuid';
    public const ROOT_KEY = 'entries';

    protected $uuid;
    protected $customer_uuid;
    protected $data_source_uuid;
    protected $customer_external_id;
    protected $first_name;
    protected $last_name;
    protected $position;
    protected $email;
    protected $title;
    protected $notes;
    protected $phone;
    protected $linked_in;
    protected $twitter;
    protected $custom;

    /**
     * Merge Contacts
     *
     * @param  string               $into
     * @param  string               $from
     * @param  ClientInterface|null $client
     * @return Contact
     */
    public static function merge($into, $from, ?ClientInterface $client = null)
    {
        $result = (new static([], $client))
            ->getClient()
            ->send("/v1/contacts/".$into."/merge/".$from, "POST");

        return new Contact($result, $client);
    }
}
