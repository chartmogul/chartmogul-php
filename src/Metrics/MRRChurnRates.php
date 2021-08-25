<?php

namespace ChartMogul\Metrics;

class MRRChurnRates extends AbstractMetric
{
    /**
     * @ignore
     */
    public const ENTRY_CLASS = MRRChurnRate::class;
    /**
     * @ignore
     */
    public const RESOURCE_PATH = '/v1/metrics/mrr-churn-rate';
    /**
     * @ignore
     */
    public const RESOURCE_NAME = 'MRR Churn Rates';
}
