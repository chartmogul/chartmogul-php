<?php

namespace ChartMogul\Enrichment;

use ChartMogul\Resource\AbstractResource;
use ChartMogul\Http\ClientInterface;
use ChartMogul\Resource\EntryTrait;
use ChartMogul\Service\AllTrait;

class Customers extends AbstractResource
{
    use EntryTrait;
    use AllTrait;

    const RESOURCE_NAME = 'Customers';
    const RESOURCE_PATH = '/v1/customers';
    const ENTRY_CLASS = Customer::class;

    protected static function getEntryClass()
    {
        return static::ENTRY_CLASS;
    }

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
