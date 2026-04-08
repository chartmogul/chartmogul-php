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
     * @return self
     */
    public static function retrieve($uuid, ?ClientInterface $client = null, array $query = [])
    {
        return (new RequestService($client))
            ->setResourceClass(static::class)
            ->get($uuid, $query);
    }

    public static function get($uuid, ?ClientInterface $client = null, array $query = [])
    {
        return static::retrieve($uuid, $client, $query);
    }
}
