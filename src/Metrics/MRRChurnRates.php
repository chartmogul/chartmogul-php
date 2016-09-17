<?php

namespace ChartMogul\Metrics;

class MRRChurnRates extends AbstractMetric
{
    const ENTRY_CLASS = MRRChurnRate::class;
    const RESOURCE_PATH = '/v1/metrics/mrr-churn-rate';
    const RESOURCE_NAME = 'MRR Churn Rates';
}
