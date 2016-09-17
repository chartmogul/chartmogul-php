<?php

namespace ChartMogul\Metrics;

use ChartMogul\Resource\AbstractResource;
use ChartMogul\Resource\EntryTrait;
use ChartMogul\Resource\PageableTrait;
use ChartMogul\Service\AllTrait;
use ChartMogul\Http\ClientInterface;

class Activities extends AbstractResource {
    const ENTRY_CLASS = Activity::class;
    const RESOURCE_PATH = '/v1/customers/:customer_uuid/activities';
    const RESOURCE_NAME = 'Activities';

    use EntryTrait;
    use AllTrait;
    use PageableTrait;

    protected static function getEntryClass(){
        return static::ENTRY_CLASS;
    }

    public function __construct(array $attr = [], ClientInterface $client = null){

        parent::__construct($attr, $client);

        $this->setEntries($this->entries);
    }
}


