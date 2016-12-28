<?php
namespace ChartMogul\Service;

use ChartMogul\Http\ClientInterface;

/**
* @codeCoverageIgnore
*/
trait UpdateTrait
{

    /**
     * Get a single resource by UUID.
     * @return resource
     */
    public static function update($uuid, array $data, ClientInterface $client = null)
    {
        return (new RequestService($client))
            ->setResource($this)
            ->update($uuid, $data);
    }
}
