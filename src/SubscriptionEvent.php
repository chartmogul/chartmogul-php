<?php

namespace ChartMogul;

use ChartMogul\Http\ClientInterface;
use ChartMogul\Resource\AbstractResource;
use ChartMogul\Service\AllTrait;
use ChartMogul\Service\CreateTrait;
use ChartMogul\Service\UpdateTrait;
use ChartMogul\Service\DestroyTrait;
use ChartMogul\Service\GetTrait;
use ChartMogul\Resource\Collection;


class SubscriptionEvent extends AbstractResource
{
    use AllTrait;
    use CreateTrait;
    use UpdateTrait;
    use DestroyTrait;
    use GetTrait;

    /**
     * @ignore
     */
    public const RESOURCE_PATH = '/v1/subscription_events';
    public const RESOURCE_ID = 'id';

    /**
     * @ignore
     */
    public const RESOURCE_NAME = 'SubscriptionEvent';

    /**
     * @ignore
     */
    public const ROOT_KEY = 'subscription_events';

    protected $id;
    protected $event_type;
    protected $event_date;
    protected $effective_date;
    protected $currency;
    protected $amount_in_cents;

    public $subscription_external_id;
    public $plan_external_id;
    public $external_id;
    public $customer_external_id;
    public $data_source_uuid;
}
