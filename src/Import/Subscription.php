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



    private function cancellation($payload)
    {
        $response = $this->getClient()->send(
            '/v1/import/subscriptions/'.$this->uuid,
            'PATCH',
            $payload
        );
        $this->cancellation_dates = $response['cancellation_dates'];
        return $this;
    }

    /**
     * Cancels a subscription that was generated from an imported invoice.
     * @param  string $cancelledAt The time at which the subscription was cancelled.
     * @return Subscription
     */
    public function cancel($cancelledAt)
    {
        return $this->cancellation([
            'cancelled_at' => $cancelledAt
        ]);
    }

    /**
     * Changes dates of cancellation for a subscription.
     * @param  array $cancellationDates The array of times (strings) at which the subscription was cancelled.
     * @return Subscription
     */
    public function setCancellationDates($cancellationDates)
    {
        return $this->cancellation([
            'cancellation_dates' => $cancellationDates
        ]);
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
