<?php

namespace ChartMogul;

use ChartMogul\Resource\AbstractResource;
use ChartMogul\Http\ClientInterface;
use ChartMogul\Resource\EntryTrait;
use ChartMogul\Service\AllTrait;
use ChartMogul\Service\GetTrait;
use ChartMogul\Service\CreateTrait;
use ChartMogul\Service\DestroyTrait;


class Customers extends AbstractResource
{
    use CreateTrait;
    use EntryTrait;
    use AllTrait;
    use GetTrait;
    use DestroyTrait;

    /**
     * @ignore
     */
    const RESOURCE_NAME = 'Customers';
    /**
     * @ignore
     */
    const RESOURCE_PATH = '/v1/customers';
    /**
     * @ignore
     */
    const ENTRY_CLASS = Customer::class;

    protected static function getEntryClass()
    {
        return static::ENTRY_CLASS;
    }

    /**
     * Constructor
     * @param array                $attr
     * @param ClientInterface|null $client
     */
    public function __construct(array $attr = [], ClientInterface $client = null)
    {

        parent::__construct($attr, $client);

        $this->setEntries($this->entries);
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
}
