<?php

namespace ChartMogul\Import;

use ChartMogul\Resource\AbstractResource;
use ChartMogul\Resource\noDestroyTrait;

/**
* @codeCoverageIgnore
*/
class Plan extends AbstractResource
{

    use noDestroyTrait;

    const RESOURCE_PATH = '/v1/import/plans';
    const ROOT_KEY = 'plans';

    protected $uuid;

    public $name;
    public $interval_count;
    public $interval_unit;
    public $external_id;

    public $data_source_uuid;
}
