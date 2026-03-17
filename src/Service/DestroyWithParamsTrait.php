<?php

namespace ChartMogul\Service;

use ChartMogul\Http\ClientInterface;

/**
* @codeCoverageIgnore
*/
trait DestroyWithParamsTrait
{
    /**
     * Delete a resource with flat params (no envelope wrapping needed).
     *
     * @return boolean
     */
    public static function destroyWithParams(array $params = [], ?ClientInterface $client = null)
    {
        return (new RequestService($client))
            ->setResourceClass(static::class)
            ->setResourcePath(static::RESOURCE_PATH)
            ->destroyWithParams($params);
    }
}
