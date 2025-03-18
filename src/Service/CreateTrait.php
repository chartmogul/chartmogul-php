<?php

namespace ChartMogul\Service;

use ChartMogul\Http\ClientInterface;

/**
* @codeCoverageIgnore
*/
trait CreateTrait
{
    /**
     * Create a Resource
     *
     * @param  array                $data
     * @param  ClientInterface|null $client
     * @return self
     */
    public static function create(array $data = [], ?ClientInterface $client = null)
    {
        return (new RequestService($client))
            ->setResourceClass(static::class)
            ->create($data);
    }
}
