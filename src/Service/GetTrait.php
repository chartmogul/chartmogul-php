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
     * @return resource
     */
    public static function get($uuid, ClientInterface $client = null)
    {
        return (new RequestService($client))
            ->setResource($this)
            ->get($uuid);
    }
}
