<?php

namespace ChartMogul\Service;

use ChartMogul\Http\ClientInterface;

/**
* @codeCoverageIgnore
*/
trait DestroyWithParamsTrait
{
    /**
     * Delete a resource
     * @return boolean
     */
    public function destroyWithParams(array $params = [])
    {
        return (new RequestService())
            ->setResource($this)
            ->destroyWithParams($params);
    }
}
