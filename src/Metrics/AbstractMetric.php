<?php

namespace ChartMogul\Metrics;

use ChartMogul\Resource\AbstractResource;
use ChartMogul\Resource\EntryTrait;
use ChartMogul\Resource\SummaryTrait;
use ChartMogul\Http\ClientInterface;
use ChartMogul\Service\AllTrait;

class AbstractMetric extends AbstractResource
{
    use EntryTrait;
    use SummaryTrait;
    use AllTrait;

    /**
     * @ignore
     */
    public const ENTRY_CLASS = self::class;

    protected static function getEntryClass()
    {
        return static::ENTRY_CLASS;
    }

    public function __construct(array $attr = [], ?ClientInterface $client = null)
    {
        parent::__construct($attr, $client);

        $this->setEntries($this->entries);
        $this->setSummary($this->summary);
    }
}
