<?php

namespace ChartMogul\Metrics;

class CustomerCounts extends AbstractMetric
{
    /**
     * @ignore
     */
    const ENTRY_CLASS = CustomerCount::class;
    /**
     * @ignore
     */
    const RESOURCE_PATH = '/v1/metrics/customer-count';
    /**
     * @ignore
     */
    const RESOURCE_NAME = 'Customer Counts';
}
