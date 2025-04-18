<?php

namespace ChartMogul;

use ChartMogul\Http\ClientInterface;
use ChartMogul\Resource\AbstractResource;
use ChartMogul\Service\AllTrait;
use ChartMogul\Service\CreateTrait;
use ChartMogul\Service\UpdateWithParamsTrait;
use ChartMogul\Service\DestroyWithParamsTrait;
use ChartMogul\Resource\Collection;
use ChartMogul\Resource\SubscriptionEventCollection;
use ChartMogul\Resource\MetaCollection;

class SubscriptionEvent extends AbstractResource
{
    use AllTrait;
    use CreateTrait;
    use UpdateWithParamsTrait;
    use DestroyWithParamsTrait;

    /**
     * @ignore
     */
    public const RESOURCE_PATH = '/v1/subscription_events';

    /**
     * @ignore
     */
    public const RESOURCE_NAME = 'SubscriptionEvent';

    /**
     * @ignore
     */
    public const ROOT_KEY = 'subscription_events';

    protected $id;

    protected $amount_in_cents;
    protected $created_at;
    protected $currency;
    protected $customer_external_id;
    protected $data_source_uuid;
    protected $effective_date;
    protected $errors;
    protected $event_date;
    protected $event_order;
    protected $event_type;
    protected $external_id;
    protected $plan_external_id;
    protected $quantity;
    protected $subscription_external_id;
    protected $subscription_set_external_id;
    protected $updated_at;

    public function __construct(array $attributes = [])
    {
        parent::__construct(isset($attributes['subscription_event']) ? $attributes['subscription_event'] : $attributes);
    }

    /**
     * @inherit
     */
    public function toArray()
    {
        return [
            'subscription_event' => $this->objectToArray($this),
        ];
    }
}
