<?php

namespace ChartMogul;

use ChartMogul\Resource\AbstractResource;
use ChartMogul\Service\GetTrait;
use ChartMogul\Service\CreateTrait;
use ChartMogul\Service\AllTrait;
use ChartMogul\Service\DestroyTrait;

/**
* @codeCoverageIgnore
* @property-read string $uuid
*/
class Plan extends AbstractResource
{

    use AllTrait;
    use CreateTrait;
    use DestroyTrait;
    use GetTrait;

    /**
     * @ignore
     */
    const RESOURCE_PATH = '/v1/plans';
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

    public static function update(array $id = [], array $data = [], ClientInterface $client = null)
    {
        return (new RequestService($client))
            ->setResourceClass(static::class)
            ->setResourcePath(static::RESOURCE_PATH . "/:plan_uuid")
            ->update($id, $data);
    }
}
