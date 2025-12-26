<?php

namespace ChartMogul\Metrics\Customers;

use ChartMogul\Resource\AbstractModel;
use ChartMogul\Http\ClientInterface;

/**
 * @property-read string $id;
 * @property-read string $uuid;
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
    protected $uuid;
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

    /**
     * Connect Subscriptions
     *
     * @param  string         $dataSourceUUID Data Source UUID
     * @param  string         $customerUUID  Customer UUID
     * @param  array|\ChartMogul\Resource\Collection|\Traversable $subscriptions Array or Collection of subscriptions to connect
     * @param  ClientInterface|null $client
     * @return bool
     */
    public static function connect($dataSourceUUID, $customerUUID, $subscriptions, ?ClientInterface $client = null)
    {
        $clientObj = $client ?? new \ChartMogul\Http\Client();

        $arr = self::getSubscriptionsForConnectDisconnect($dataSourceUUID, $subscriptions);

        $clientObj->send(
            '/v1/customers/' . $customerUUID . '/connect_subscriptions',
            'POST',
            ['subscriptions' => $arr]
        );

        return true;
    }

    /**
     * Disconnect Subscriptions
     *
     * @param  string         $dataSourceUUID Data Source UUID
     * @param  string         $customerUUID  Customer UUID
     * @param  array|\ChartMogul\Resource\Collection|\Traversable $subscriptions Array or Collection of subscriptions to disconnect
     * @param  ClientInterface|null $client
     * @return bool
     */
    public static function disconnect($dataSourceUUID, $customerUUID, $subscriptions, ?ClientInterface $client = null)
    {
        $clientObj = $client ?? new \ChartMogul\Http\Client();

        $arr = self::getSubscriptionsForConnectDisconnect($dataSourceUUID, $subscriptions);

        $clientObj->send(
            '/v1/customers/' . $customerUUID . '/disconnect_subscriptions',
            'POST',
            ['subscriptions' => $arr]
        );

        return true;
    }

    /**
     * Prepare subscriptions array for API request
     *
     * @param  string $dataSourceUUID Data Source UUID
     * @param  array|\ChartMogul\Resource\Collection|\Traversable $subscriptions Subscriptions to prepare
     * @return array
     */
    private static function getSubscriptionsForConnectDisconnect($dataSourceUUID, $subscriptions)
    {
        $arr = [];
        foreach ($subscriptions as $subscription) {
            $arr[] = [
                'data_source_uuid' => $dataSourceUUID,
                'uuid' => $subscription->uuid
            ];
        }
        return $arr;
    }
}
