<?php

namespace ChartMogul\Metrics;

use ChartMogul\Resource\AbstractModel;

/**
 * @property-read string $date;
 * @property-read int $mrr;
 * @property-read string $percentage_change;
 * @property-read int $mrr_new_business;
 * @property-read int $mrr_expansion;
 * @property-read int $mrr_contraction;
 * @property-read int $mrr_churn;
 * @property-read int $mrr_reactivation;
 */
class MRR extends AbstractModel
{
    protected $date;
    protected $mrr;
    protected $percentage_change;

    protected $mrr_new_business;

    protected $mrr_expansion;
    protected $mrr_contraction;

    protected $mrr_churn;
    protected $mrr_reactivation;
}
