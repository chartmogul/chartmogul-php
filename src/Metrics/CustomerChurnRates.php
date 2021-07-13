<?php

namespace ChartMogul\Metrics;

class CustomerChurnRates extends AbstractMetric
{
    /**
     * @ignore
     */
    public const ENTRY_CLASS = CustomerChurnRate::class;
    /**
     * @ignore
     */
    public const RESOURCE_PATH = '/v1/metrics/customer-churn-rate';
    /**
     * @ignore
     */
    public const RESOURCE_NAME = 'Customer Churn Rates';
}
