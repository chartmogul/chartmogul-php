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
     * @return resource
     */
    public static function retrieve(?ClientInterface $client = null, array $query = [])
    {
        $requestService = (new RequestService($client))
            ->setResourceClass(static::class);

        if (empty($query)) {
            return $requestService->get();
        }

        return $requestService->getWithQuery(null, $query);
    }
}
