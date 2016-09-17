<?php

namespace ChartMogul\Metrics;

use ChartMogul\Resource\AbstractResource;
use ChartMogul\Resource\EntryTrait;
use ChartMogul\Service\AllTrait;
use ChartMogul\Http\ClientInterface;

class AllKeyMetrics extends AbstractResource
{
    const ENTRY_CLASS = AllKeyMetric::class;
    const RESOURCE_PATH = '/v1/metrics/all';
    const RESOURCE_NAME = 'All Key Metrics';

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
