<?php
namespace ChartMogul\Service;

use ChartMogul\Http\ClientInterface;

/**
* @codeCoverageIgnore
*/
trait DestroyTrait
{

    public function destroy()
    {

        return (new RequestService())
            ->setResource($this)
            ->destroy();
    }
}
