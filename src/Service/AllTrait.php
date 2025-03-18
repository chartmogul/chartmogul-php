<?php

namespace ChartMogul\Service;

use ChartMogul\Http\ClientInterface;
use ChartMogul\Resource\Collection;

/**
* @codeCoverageIgnore
*/
trait AllTrait
{
    /**
     * Returns a list of objects
     *
     * @param  array                $data
     * @param  ClientInterface|null $client 0
     * @return Collection|self[]|self
     */
    public static function all(array $data = [], ?ClientInterface $client = null)
    {
        return (new RequestService($client))
            ->setResourceClass(static::class)
            ->all($data);
    }
}
