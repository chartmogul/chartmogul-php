<?php

namespace ChartMogul;

use ChartMogul\Http\ClientInterface;
use ChartMogul\Resource\AbstractResource;
use ChartMogul\Service\AllTrait;
use ChartMogul\Service\CreateTrait;
use ChartMogul\Service\UpdateWithParamsTrait;
use ChartMogul\Service\DestroyWithParamsTrait;
use ChartMogul\Resource\Collection;

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

    public $external_id;
    public $data_source_uuid;
    public $customer_external_id;
    public $event_type;
    public $event_date;
    public $effective_date;
    public $currency;
    public $amount_in_cents;
    public $subscription_external_id;
    public $plan_external_id;

}
