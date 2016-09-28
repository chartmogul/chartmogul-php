<?php

namespace ChartMogul\Metrics;

class MRRChurnRates extends AbstractMetric
{
    /**
     * @ignore
     */
    const ENTRY_CLASS = MRRChurnRate::class;
    /**
     * @ignore
     */
    const RESOURCE_PATH = '/v1/metrics/mrr-churn-rate';
    /**
     * @ignore
     */
    const RESOURCE_NAME = 'MRR Churn Rates';
}
