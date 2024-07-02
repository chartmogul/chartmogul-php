<?php

namespace ChartMogul\LineItems;

/**
 * @codeCoverageIgnore
 */
class Subscription extends AbstractLineItem
{
    public $type = 'subscription';

    public $cancelled_at;
    public $prorated;
    public $proration_type;
    public $service_period_end;
    public $service_period_start;
    public $subscription_external_id;
    public $subscription_set_external_id;

    protected $subscription_uuid;
}
