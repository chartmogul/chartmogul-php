<?php

namespace ChartMogul\Resource;

use ChartMogul\Http\ClientInterface;

/**
* @codeCoverageIgnore
*/
trait noDestroyTrait
{

    public function destroy()
    {

        throw new \Exception('method destroy not implemented for '.static::class);
    }
}
