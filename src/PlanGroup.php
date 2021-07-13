<?php

namespace ChartMogul;

use ChartMogul\Resource\AbstractResource;
use ChartMogul\Service\GetTrait;
use ChartMogul\Service\CreateTrait;
use ChartMogul\Service\AllTrait;
use ChartMogul\Service\UpdateTrait;
use ChartMogul\Service\DestroyTrait;

/**
* @codeCoverageIgnore
* @property-read string $uuid
*/
class PlanGroup extends AbstractResource
{
    use AllTrait;
    use CreateTrait;
    use UpdateTrait;
    use DestroyTrait;
    use GetTrait;

    /**
     * @ignore
     */
    public const RESOURCE_PATH = '/v1/plan_groups';
    public const RESOURCE_ID = 'plan_group_uuid';
    /**
     * @ignore
     */
    public const RESOURCE_NAME = 'PlanGroup';
    /**
     * @ignore
     */
    public const ROOT_KEY = 'plan_groups';

    protected $uuid;
    protected $plans_count;

    public $name;
    public $plans = [];
}
