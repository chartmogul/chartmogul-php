<?php

namespace ChartMogul\Metrics;

use ChartMogul\Resource\AbstractModel;
use ChartMogul\Http\ClientInterface;

/**
 * @property-read string $description;
 * @property-read string $type;
 * @property-read string $date;
 * @property-read string $activity_arr;
 * @property-read string $activity_mrr;
 * @property-read string $activity_mrr_movement;
 * @property-read string $currency;
 * @property-read string $subscription_external_id
 * @property-read string $plan_external_id
 * @property-read string $customer_name
 * @property-read string $customer_uuid
 * @property-read string $customer_external_id
 * @property-read string $billing_connector_uuid
 * @property-read string $uuid
 */
class Activity extends AbstractModel
{
    protected $description;
    protected $type;
    protected $date;
    protected $activity_arr;
    protected $activity_mrr;
    protected $activity_mrr_movement;
    protected $currency;
    protected $subscription_external_id;
    protected $plan_external_id;
    protected $customer_name;
    protected $customer_uuid;
    protected $customer_external_id;
    protected $billing_connector_uuid;
    protected $uuid;

    /**
     * Returns a list of activities for a given customer.
     *
     * @param  array                $options array with `customer_uuid`
     * @param  ClientInterface|null $client
     * @return Activities
     */
    public static function all(array $options = [], ?ClientInterface $client = null)
    {
        return Activities::all($options, $client);
    }
}
