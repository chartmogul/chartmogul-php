<?php

namespace ChartMogul\Metrics;

class MRRs extends AbstractMetric
{
    /**
     * @ignore
     */
    public const ENTRY_CLASS = MRR::class;
    /**
     * @ignore
     */
    public const RESOURCE_PATH = '/v1/metrics/mrr';
    /**
     * @ignore
     */
    public const RESOURCE_NAME = 'MRRs';
}
