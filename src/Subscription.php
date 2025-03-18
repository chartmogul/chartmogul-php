<?php

namespace ChartMogul;

use ChartMogul\Resource\AbstractResource;
use ChartMogul\Resource\Collection;
use ChartMogul\Service\CreateTrait;
use ChartMogul\Service\AllTrait;
use ChartMogul\Http\ClientInterface;

/**
 * @property-read string $uuid
 * @property-read string $customer_uuid
 * @property-read string $external_id
 * @property-read string $subscription_set_external_id
 * @property-read string $cancellation_dates
 * @property-read string $plan_uuid
 * @property-read string $data_source_uuid
 */
class Subscription extends AbstractResource
{
    use CreateTrait;
    use AllTrait;

    /**
     * @ignore
     */
    public const RESOURCE_PATH = '/v1/import/customers/:customer_uuid/subscriptions';
    /**
     * @ignore
     */
    public const ROOT_KEY = 'subscriptions';

    public const RESOURCE_NAME = 'Subscription';


    protected $uuid;
    protected $external_id;
    protected $subscription_set_external_id;
    protected $cancellation_dates;

    protected $plan_uuid;
    protected $data_source_uuid;
    protected $customer_uuid;



    private function cancellation($payload)
    {
        $response = $this->getClient()->send(
            '/v1/import/subscriptions/'.$this->uuid,
            'PATCH',
            $payload
        );
        foreach ($response as $key => $value) {
            // replace property names with dash with underscores
            $key = str_replace('-', '_', $key);
            $this->$key = $value;
        }
        return $this;
    }

    /**
     * Cancels a subscription that was generated from an imported invoice.
     *
     * @param  string $cancelledAt The time at which the subscription was cancelled.
     * @return Subscription
     */
    public function cancel($cancelledAt)
    {
        return $this->cancellation(
            [
            'cancelled_at' => $cancelledAt
            ]
        );
    }

    /**
     * Changes dates of cancellation for a subscription.
     *
     * @param  array $cancellationDates The array of times (strings) at which the subscription was cancelled.
     * @return Subscription
     */
    public function setCancellationDates($cancellationDates)
    {
        return $this->cancellation(
            [
            'cancellation_dates' => $cancellationDates
            ]
        );
    }

    /**
     * @inheritDoc
     */
    public static function fromArray(array $data, ?ClientInterface $client = null)
    {
        $result = parent::fromArray($data, $client);
        if (isset($data["customer_uuid"]) && $result instanceof Collection) {
            $result->customer_uuid = $data["customer_uuid"];
        }
        return $result;
    }

    /**
     * Connect Subscriptions
     *
     * @param  string         $customerUUID  Customer UUID
     * @param  Subscription[] $subscriptions Array of Subscription to connect this subscription with
     * @return bool
     */
    public function connect($customerUUID, array $subscriptions)
    {
        $arr = [];
        for ($i = 0; $i < count($subscriptions); $i++) {
            $arr[$i] = $subscriptions[$i];
            if ($subscriptions[$i] instanceof Subscription) {
                $arr[$i] = $subscriptions[$i]->toArray();
            }
        }

        array_unshift($arr, $this->toArray());

        $this->getClient()
            ->send(
                '/v1/customers/'.$customerUUID.'/connect_subscriptions',
                'POST',
                [
                'subscriptions' => $arr,
                ]
            );
        return true;
    }
}
