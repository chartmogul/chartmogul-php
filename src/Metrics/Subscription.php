<?php

namespace ChartMogul\Metrics;

use ChartMogul\Resource\AbstractModel;
use ChartMogul\Http\ClientInterface;

class Subscription extends AbstractModel {

    protected $id;
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
     * @param  array $options array with `customer_uuid`
     * @param  ClientInterface|null $client
     * @return Subscriptions
     */
    public static function all(array $options= [], ClientInterface $client = null){

        return Subscriptions::all($options, $client);
    }
}


