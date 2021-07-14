<?php

namespace ChartMogul\Metrics;

class CustomerCounts extends AbstractMetric
{
    /**
     * @ignore
     */
    public const ENTRY_CLASS = CustomerCount::class;
    /**
     * @ignore
     */
    public const RESOURCE_PATH = '/v1/metrics/customer-count';
    /**
     * @ignore
     */
    public const RESOURCE_NAME = 'Customer Counts';
}
