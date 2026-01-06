<?php

namespace ChartMogul\Service;

use ChartMogul\Http\ClientInterface;

/**
* @codeCoverageIgnore
*/
trait GetTrait
{
    /**
     * Get a single resource by UUID.
     *
     * @return resource
     */
    public static function retrieve($uuid, ?ClientInterface $client = null, array $query = [])
    {
        $requestService = (new RequestService($client))
            ->setResourceClass(static::class);

        if (empty($query)) {
            return $requestService->get($uuid);
        }

        return $requestService->getWithQuery($uuid, $query);
    }

    public static function get($uuid, ?ClientInterface $client = null, array $query = [])
    {
        return static::retrieve($uuid, $client, $query);
    }
}
