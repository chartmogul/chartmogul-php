<?php

namespace ChartMogul;

use ChartMogul\Resource\AbstractResource;
use ChartMogul\Service\AllTrait;
use ChartMogul\Service\CreateTrait;
use ChartMogul\Service\UpdateTrait;
use ChartMogul\Service\DestroyTrait;
use ChartMogul\Service\GetTrait;
use ChartMogul\Service\FromArrayTrait;


/**
 * @property-read string $uuid
 * @property-read string $customer_uuid
 * @property-read string $owner
 * @property-read string $pipeline
 * @property-read string $pipeline_stage
 * @property-read string $estimated_close_date
 * @property-read string $currency
 * @property-read integer $amount_in_cents
 * @property-read string $type
 * @property-read string $forecast_category
 * @property-read integer $win_likelihood
 * @property-read array $custom
 * @property-read string $created_at
 * @property-read string $updated_at
 */
class Opportunity extends AbstractResource
{
    use AllTrait;
    use CreateTrait;
    use GetTrait;
    use DestroyTrait;
    use UpdateTrait;
    use FromArrayTrait;

    /**
     * @ignore
     */
    public const RESOURCE_NAME = 'Opportunity';
    /**
     * @ignore
     */
    public const RESOURCE_PATH = '/v1/opportunities';
    public const RESOURCE_ID = 'uuid';
    public const ROOT_KEY = 'entries';

    protected $uuid;
    protected $customer_uuid;
    protected $owner;
    protected $pipeline;
    protected $pipeline_stage;
    protected $estimated_close_date;
    protected $currency;
    protected $amount_in_cents;
    protected $type;
    protected $forecast_category;
    protected $win_likelihood;
    protected $custom;
    protected $created_at;
    protected $updated_at;
}
