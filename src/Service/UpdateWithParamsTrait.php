<?php

namespace ChartMogul\Service;

use ChartMogul\Http\ClientInterface;

/**
* @codeCoverageIgnore
*/
trait UpdateWithParamsTrait
{
    /**
     * Update a Resource
     *
     * @param  array                $params
     * @param  ClientInterface|null $client
     * @return self
     */
    public static function updateWithParams(array $params = [], ?ClientInterface $client = null)
    {
        return (new RequestService($client))
            ->setResourceClass(static::class)
            ->setResourcePath(static::RESOURCE_PATH)
            ->updateWithParams($params);
    }
}
