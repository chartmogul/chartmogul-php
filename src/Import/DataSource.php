<?php

namespace ChartMogul\Import;

use ChartMogul\Resource\AbstractResource;

/**
* @codeCoverageIgnore
* @property-read string $uuid
* @property-read string $status
* @property-read string $created_at
*/
class DataSource extends AbstractResource
{

    use \ChartMogul\Service\CreateTrait;
    use \ChartMogul\Service\AllTrait;
    use \ChartMogul\Service\DestroyTrait;

    /**
    * @ignore
    */
    const RESOURCE_PATH = '/v1/import/data_sources';

    /**
    * @ignore
    */
    const RESOURCE_NAME = 'Data Source';

    /**
    * @ignore
    */
    const ROOT_KEY = 'data_sources';

    protected $uuid;
    protected $status;
    protected $created_at;
    public $name;
}
