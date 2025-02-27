<?php

namespace ChartMogul\Metrics;

use ChartMogul\Resource\AbstractResource;
use ChartMogul\Resource\EntryTrait;
use ChartMogul\Service\AllTrait;
use ChartMogul\Http\ClientInterface;

class AllKeyMetrics extends AbstractResource
{
    use EntryTrait;
    use AllTrait;
    /**
     * @ignore
     */
    public const ENTRY_CLASS = AllKeyMetric::class;
    /**
     * @ignore
     */
    public const RESOURCE_PATH = '/v1/metrics/all';
    /**
     * @ignore
     */
    public const RESOURCE_NAME = 'All Key Metrics';

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
