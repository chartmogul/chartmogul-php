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
    const RESOURCE_PATH = '/v1/plan_groups';
    const RESOURCE_ID = 'plan_group_uuid';
    /**
     * @ignore
     */
    const RESOURCE_NAME = 'PlanGroup';
    /**
     * @ignore
     */
    const ROOT_KEY = 'plan_groups';

    protected $uuid;
    protected $plans_count;

    public $name;
    public $plans = [];
}
