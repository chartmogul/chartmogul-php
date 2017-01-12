<?php

namespace ChartMogul\Metrics;

use ChartMogul\Resource\AbstractResource;
use ChartMogul\Resource\EntryTrait;
use ChartMogul\Service\AllTrait;
use ChartMogul\Http\ClientInterface;

class Subscriptions extends AbstractResource
{
    /**
     * @ignore
     */
    const ENTRY_CLASS = Subscription::class;
    /**
     * @ignore
     */
    const RESOURCE_PATH = '/v1/customers/:customer_uuid/subscriptions';
    /**
     * @ignore
     */
    const RESOURCE_NAME = 'Subscriptions';

    use EntryTrait;
    use AllTrait;

    protected static function getEntryClass()
    {
        return static::ENTRY_CLASS;
    }

    public function __construct(array $attr = [], ClientInterface $client = null)
    {

        parent::__construct($attr, $client);

        $this->setEntries($this->entries);
    }
}
