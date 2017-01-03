<?php

namespace ChartMogul\Import;

use ChartMogul\Resource\AbstractResource;
use ChartMogul\Service\CreateTrait;
use ChartMogul\Service\AllTrait;
use ChartMogul\Service\DestroyTrait;
use ChartMogul\Service\GetTrait;

/**
 * @codeCoverageIgnore
 * @property-read string $uuid
 * @property-read string $status
 * @property-read string $created_at
 */
class DataSource extends AbstractResource {

    use CreateTrait;
    use AllTrait;
    use DestroyTrait;
    use GetTrait;

    /**
     * @ignore
     */
    const RESOURCE_PATH = '/v1/data_sources';

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
