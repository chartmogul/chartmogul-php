<?php

namespace ChartMogul\PlanGroups;

use ChartMogul\Resource\AbstractResource;
use ChartMogul\Service\AllTrait;

/**
* @codeCoverageIgnore
* @property-read string $uuid
*/
class Plan extends AbstractResource
{
    use AllTrait;

    /**
     * @ignore
     */
    const RESOURCE_PATH = '/v1/plan_groups/:plan_group_uuid/plans';
    /**
     * @ignore
     */
    const ROOT_KEY = 'plans';

    protected $uuid;

    public $name;
    public $interval_count;
    public $interval_unit;
    public $external_id;
    public $data_source_uuid;
}
