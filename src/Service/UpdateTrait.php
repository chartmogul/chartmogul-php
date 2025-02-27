<?php

namespace ChartMogul\Service;

use ChartMogul\Http\ClientInterface;

/**
* @codeCoverageIgnore
*/
trait UpdateTrait
{
    /**
     * Update a Resource
     *
     * @param  array                $data
     * @param  ClientInterface|null $client
     * @return self
     */
    public static function update(array $id = [], array $data = [], ?ClientInterface $client = null)
    {
        return (new RequestService($client))
            ->setResourceClass(static::class)
            ->setResourcePath(static::RESOURCE_PATH . "/:" . static::RESOURCE_ID)
            ->update($id, $data);
    }
}
