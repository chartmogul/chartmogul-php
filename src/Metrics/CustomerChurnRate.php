<?php

namespace ChartMogul\Metrics;

use ChartMogul\Resource\AbstractModel;

/**
 * @property-read string $data
 * @property-read string $customer_churn_rate
 */
class CustomerChurnRate extends AbstractModel {
    protected $date;
    protected $customer_churn_rate;
}