<?php

namespace ChartMogul;

use ChartMogul\Http\ClientInterface;
use ChartMogul\Resource\AbstractResource;
use ChartMogul\Service\AllTrait;
use ChartMogul\Service\CreateTrait;
use ChartMogul\Service\UpdateWithParamsTrait;
use ChartMogul\Service\DestroyWithParamsTrait;
use ChartMogul\Resource\CollectionWithCursor;
use ChartMogul\Resource\SubscriptionEventCollection;

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
    protected $external_id;
    protected $data_source_uuid;
    protected $customer_external_id;
    protected $event_type;
    protected $event_date;
    protected $effective_date;
    protected $currency;
    protected $amount_in_cents;
    protected $subscription_external_id;
    protected $plan_external_id;
}
