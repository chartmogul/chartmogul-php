<?php

namespace ChartMogul\Metrics;

use ChartMogul\Resource\AbstractModel;
use ChartMogul\Http\ClientInterface;

class Activity extends AbstractModel {

    protected $id;
    protected $description;
    protected $type;
    protected $date;
    protected $activity_arr;
    protected $activity_mrr;
    protected $activity_mrr_movement;
    protected $currency;
    protected $currency_sign;

    /**
     * Returns a list of activities for a given customer.
     * @param  array $options array with `customer_uuid`
     * @param  ClientInterface|null $client
     * @return Activities
     */
    public static function all(array $options= [], ClientInterface $client = null){

        return Activities::all($options, $client);
    }
}


