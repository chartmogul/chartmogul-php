<?php

namespace ChartMogul\Service;

use ChartMogul\Http\ClientInterface;

/**
* @codeCoverageIgnore
*/
trait DestroyTrait
{
    /**
     * Delete a resource
     *
     * @return boolean
     */
    public function destroy()
    {
        return (new RequestService())
            ->setResource($this)
            ->destroy();
    }
}
