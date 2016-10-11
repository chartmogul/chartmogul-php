<?php

namespace ChartMogul\Import;

use ChartMogul\Resource\AbstractResource;

/**
* @codeCoverageIgnore
* @property-read string $uuid
*/
class Plan extends AbstractResource
{

    use \ChartMogul\Service\CreateTrait;
    use \ChartMogul\Service\AllTrait;

    /**
     * @ignore
     */
    const RESOURCE_PATH = '/v1/import/plans';
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
