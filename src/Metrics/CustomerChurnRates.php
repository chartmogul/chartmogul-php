<?php

namespace ChartMogul\Metrics;

class CustomerChurnRates extends AbstractMetric {
    const ENTRY_CLASS = CustomerChurnRate::class;
    const RESOURCE_PATH = '/v1/metrics/customer-churn-rate';
    const RESOURCE_NAME = 'Customer Churn Rates';
}


