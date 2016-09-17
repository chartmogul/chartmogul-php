<?php

namespace ChartMogul\Metrics;

use ChartMogul\Resource\AbstractModel;

class MRR extends AbstractModel {
    protected $date;
    protected $mrr;

    protected $mrr_new_business;

    protected $mrr_expansion;
    protected $mrr_contraction;

    protected $mrr_churn;
    protected $mrr_reactivation;
}