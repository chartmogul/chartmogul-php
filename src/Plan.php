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
class Plan extends AbstractResource
{

    use AllTrait;
    use CreateTrait;
    use UpdateTrait;
    use DestroyTrait;
    use GetTrait;

    /**
     * @ignore
     */
    const RESOURCE_PATH = '/v1/plans';
    const RESOURCE_ID = 'plan_uuid';
    /**
     * @ignore
     */
    const RESOURCE_NAME = 'Plan';
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
