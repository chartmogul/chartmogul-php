<?php

namespace ChartMogul\Import;

use ChartMogul\Resource\AbstractResource;

/**
 * @property-read string $uuid
 * @property-read string $external_id
 * @property-read string $cancellation_dates
 * @property-read string $plan_uuid
 * @property-read string $data_source_uuid
 */
class Subscription extends AbstractResource
{

    use \ChartMogul\Service\CreateTrait;
    use \ChartMogul\Service\AllTrait;
    
    /**
     * @ignore
     */
    const RESOURCE_PATH = '/v1/import/customers/:customer_uuid/subscriptions';
    /**
     * @ignore
     */
    const ROOT_KEY = 'subscriptions';

    const RESOURCE_NAME = 'Subscription';


    protected $uuid;
    protected $external_id;
    protected $cancellation_dates = [];

    protected $plan_uuid;
    protected $data_source_uuid;

    /**
     * Cancels a subscription that was generated from an imported invoice.
     * @param  string $cancelledAt The time at which the subscription was cancelled.
     * @return Subscription
     */
    public function cancel($cancelledAt)
    {
        $response = $this->getClient()->send(
            '/v1/import/subscriptions/'.$this->uuid,
            'PATCH',
            [
            'cancelled_at' => $cancelledAt
            ]
        );
        $this->cancellation_dates = $response['cancellation_dates'];
        return $this;
    }
    /**
     * @param array $data
     * @param ClientInterface|null $client
     * @return ArrayCollection|self
     */
    public static function fromArray(array $data, \ChartMogul\Http\ClientInterface $client = null) {
        $result = parent::fromArray($data, $client);
        if (isset($data["customer_uuid"])) {
            $result->customer_uuid = $data["customer_uuid"];
        }
        return $result;
    }
}
