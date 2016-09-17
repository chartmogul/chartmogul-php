<?php

namespace ChartMogul\Metrics;

class CustomerCounts extends AbstractMetric {
    const ENTRY_CLASS = CustomerCount::class;
    const RESOURCE_PATH = '/v1/metrics/customer-count';
    const RESOURCE_NAME = 'Customer Counts';
}


