<?php

namespace ChartMogul\Service;

use ChartMogul\Http\ClientInterface;

/**
* @codeCoverageIgnore
*/
trait ShowTrait
{
    /**
     * Show details of current resource.
     *
     * @param  ClientInterface|null $client
     * @param  array                $query  Optional query parameters
     * @return self
     */
    public static function retrieve(?ClientInterface $client = null, array $query = [])
    {
        return (new RequestService($client))
            ->setResourceClass(static::class)
            ->get(null, $query);
    }
}
