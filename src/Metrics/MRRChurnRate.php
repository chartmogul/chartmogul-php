<?php

namespace ChartMogul\Metrics;

use ChartMogul\Resource\AbstractModel;

/**
 * @property-read string $data
 * @property-read string $mrr_churn_rate
 */
class MRRChurnRate extends AbstractModel {
    protected $date;
    protected $mrr_churn_rate;
}