<?php

namespace ChartMogul\Metrics\Customers;

use ChartMogul\Resource\AbstractResource;
use ChartMogul\Resource\EntryTrait;
use ChartMogul\Service\AllTrait;
use ChartMogul\Http\ClientInterface;

class Activities extends AbstractResource
{
    use EntryTrait;
    use AllTrait;
    /**
     * @ignore
     */
    public const ENTRY_CLASS = Activity::class;
    /**
     * @ignore
     */
    public const RESOURCE_PATH = '/v1/customers/:customer_uuid/activities';
    /**
     * @ignore
     */
    public const RESOURCE_NAME = 'Activities';

    protected static function getEntryClass()
    {
        return static::ENTRY_CLASS;
    }

    public function __construct(array $attr = [], ?ClientInterface $client = null)
    {
        parent::__construct($attr, $client);

        $this->setEntries($this->entries);
    }
}
