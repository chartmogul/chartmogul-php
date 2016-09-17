<?php

namespace ChartMogul\Metrics;

use ChartMogul\Resource\AbstractModel;

/**
 * @property-read string $data
 * @property-read string $ltv
 */
class LTV extends AbstractModel {
    protected $date;
    protected $ltv;
}