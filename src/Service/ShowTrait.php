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
     * @return resource
     */

    public static function retrieve(?ClientInterface $client = null)
    {
        return (new RequestService($client))
            ->setResourceClass(static::class)
            ->get();
    }
}
