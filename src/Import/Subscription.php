<?php

namespace ChartMogul\Import;

use ChartMogul\Resource\AbstractResource;
use ChartMogul\Resource\noDestroyTrait;
use ChartMogul\Resource\noCreateTrait;

class Subscription extends AbstractResource
{

    use noDestroyTrait;
    use noCreateTrait;

    const RESOURCE_PATH = '/v1/import/customers/:customer_uuid/subscriptions';
    const ROOT_KEY = 'subscriptions';

    private $customer_uuid;

    protected $uuid;
    protected $external_id;
    protected $cancellation_dates = [];

    protected $plan_uuid;
    protected $data_source_uuid;


    protected function applyAttributes(array &$data = [])
    {
        $this->customer_uuid = $data['customer_uuid'];
        unset($data['customer_uuid']);
        return parent::applyAttributes($data);
    }

    public function getResourcePath()
    {
        if (empty($this->customer_uuid)) {
            throw new \Exception('customer_uuid parameter missing');
        }

        return str_replace(':customer_uuid', $this->customer_uuid, static::RESOURCE_PATH);
    }

    public function cancel($cancelledAt)
    {
        $response = $this->custom(
            '/v1/import/subscriptions/'.$this->uuid,
            'PATCH',
            [
            'cancelled_at' => $cancelledAt
            ]
        );
        $this->cancellation_dates = $response['cancellation_dates'];
        return $this;
    }
}
