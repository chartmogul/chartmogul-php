<?php

namespace ChartMogul\LineItems;

/**
 * @codeCoverageIgnore
 */
class OneTime extends AbstractLineItem
{
    public $type = 'one_time';
    public $description;
    public $plan_uuid;

    public $subscription_uuid;
    public $subscription_external_id;
    public $subscription_set_external_id;
    public $service_period_start;
    public $service_period_end;
    public $cancelled_at;
    public $prorated;
    public $account_code;
}
