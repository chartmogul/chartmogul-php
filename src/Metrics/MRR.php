<?php

namespace ChartMogul\Metrics;

use ChartMogul\Resource\AbstractModel;

/**
 * @property-read $date;
 * @property-read $mrr;
 * @property-read $mrr_new_business;
 * @property-read $mrr_expansion;
 * @property-read $mrr_contraction;
 * @property-read $mrr_churn;
 * @property-read $mrr_reactivation;
 */
class MRR extends AbstractModel
{
    protected $date;
    protected $mrr;

    protected $mrr_new_business;

    protected $mrr_expansion;
    protected $mrr_contraction;

    protected $mrr_churn;
    protected $mrr_reactivation;
}
