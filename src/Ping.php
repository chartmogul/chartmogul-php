<?php

namespace ChartMogul;

use ChartMogul\Http\ClientInterface;
use ChartMogul\Resource\AbstractResource;
use ChartMogul\Service\AllTrait;

/**
 * @codeCoverageIgnore
 * @property-read      string $uuid
 */
class Ping extends AbstractResource
{
    use AllTrait;

    /**
     * @ignore
     */
    public const RESOURCE_PATH = '/v1/ping';
    /**
     * @ignore
     */
    public const RESOURCE_NAME = 'Ping';

    public $data;

    public static function ping(?ClientInterface $client = null)
    {
        return Ping::all([], $client);
    }
}
