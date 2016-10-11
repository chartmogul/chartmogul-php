<?php

namespace ChartMogul\Metrics;

use ChartMogul\Resource\AbstractModel;

/**
 * @property-read string $data
 * @property-read string $customers
 */
class CustomerCount extends AbstractModel
{
    protected $date;
    protected $customers;
}
