<?php

namespace ChartMogul\Import;

use ChartMogul\Resource\AbstractResource;

/**
* @codeCoverageIgnore
*/
class DataSource extends AbstractResource
{

    const RESOURCE_PATH = '/v1/import/data_sources';
    const ROOT_KEY = 'data_sources';

    protected $uuid;
    protected $status;
    protected $created_at;
    public $name;
}
