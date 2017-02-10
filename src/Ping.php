<?php

namespace ChartMogul;

use ChartMogul\Resource\AbstractResource;
use ChartMogul\Service\AllTrait;

/**
* @codeCoverageIgnore
* @property-read string $uuid
*/
class Ping extends AbstractResource
{
    use AllTrait;

    /**
     * @ignore
     */
    const RESOURCE_PATH = '/v1/ping';
    /**
     * @ignore
     */
    const RESOURCE_NAME = 'Ping';

    public $data;

    public static function ping(ClientInterface $client = null)
    {
        return Ping::all();
    }
}
