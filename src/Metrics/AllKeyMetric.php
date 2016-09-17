<?php

namespace ChartMogul\Metrics;

use ChartMogul\Resource\AbstractModel;

/**
 * @property-read string $date;
 * @property-read string $arpa;
 * @property-read string $arr;
 * @property-read string $asp;
 * @property-read string $customer_churn_rate;
 * @property-read string $customers;
 * @property-read string $ltv;
 * @property-read string $mrr;
 * @property-read string $mrr_churn_rate;
 */
class AllKeyMetric extends AbstractModel {
    protected $date;
    protected $arpa;
    protected $arr;
    protected $asp;
    protected $customer_churn_rate;
    protected $customers;
    protected $ltv;
    protected $mrr;
    protected $mrr_churn_rate;
}