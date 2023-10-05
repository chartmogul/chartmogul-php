<?php

namespace ChartMogul\LineItems;

/**
 * @codeCoverageIgnore
 */
class Subscription extends AbstractLineItem
{
    public $type = 'subscription';
    public $subscription_external_id;
    public $subscription_set_external_id;
    public $service_period_start;
    public $service_period_end;
    public $cancelled_at;
    public $prorated;

    protected $subscription_uuid;
    public $plan_uuid;
}
