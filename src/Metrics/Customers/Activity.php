<?php

namespace ChartMogul\Metrics\Customers;

use ChartMogul\Resource\AbstractModel;
use ChartMogul\Http\ClientInterface;

/**
 * @property-read string $id;
 * @property-read string $description;
 * @property-read string $type;
 * @property-read string $date;
 * @property-read string $activity_arr;
 * @property-read string $activity_mrr;
 * @property-read string $activity_mrr_movement;
 * @property-read string $currency;
 * @property-read string $currency_sign;
 */
class Activity extends AbstractModel
{
    protected $id;

    protected $activity_arr;
    protected $activity_mrr;
    protected $activity_mrr_movement;
    protected $currency;
    protected $currency_sign;
    protected $date;
    protected $description;
    protected $subscription_external_id;
    protected $type;

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
