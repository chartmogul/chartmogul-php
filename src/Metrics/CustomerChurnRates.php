<?php

namespace ChartMogul\Metrics;

class CustomerChurnRates extends AbstractMetric
{
    /**
     * @ignore
     */
    const ENTRY_CLASS = CustomerChurnRate::class;
    /**
     * @ignore
     */
    const RESOURCE_PATH = '/v1/metrics/customer-churn-rate';
    /**
     * @ignore
     */
    const RESOURCE_NAME = 'Customer Churn Rates';
}
