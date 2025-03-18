<?php

namespace ChartMogul\Metrics\Customers;

use ChartMogul\Resource\AbstractModel;
use ChartMogul\Http\ClientInterface;

/**
 * @property-read string $id;
 * @property-read string $plan;
 * @property-read string $quantity;
 * @property-read string $mrr;
 * @property-read string $arr;
 * @property-read string $status;
 * @property-read string $billing_cycle;
 * @property-read string $billing_cycle_count;
 * @property-read string $start_date;
 * @property-read string $end_date;
 * @property-read string $currency;
 * @property-read string $currency_sign;
 */
class Subscription extends AbstractModel
{
    protected $id;
    protected $external_id;
    protected $plan;
    protected $quantity;
    protected $mrr;
    protected $arr;
    protected $status;
    protected $billing_cycle;
    protected $billing_cycle_count;
    protected $start_date;
    protected $end_date;
    protected $currency;
    protected $currency_sign;

    /**
     * Returns a list of subscriptions for a given customer.
     *
     * @param  array                $options array with `customer_uuid`
     * @param  ClientInterface|null $client
     * @return Subscriptions
     */
    public static function all(array $options = [], ?ClientInterface $client = null)
    {
        return Subscriptions::all($options, $client);
    }
}
