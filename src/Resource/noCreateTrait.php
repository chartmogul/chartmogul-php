<?php

namespace ChartMogul\Resource;

use ChartMogul\Http\ClientInterface;

/**
* @codeCoverageIgnore
*/
trait noCreateTrait
{

    public static function create(array $data = [], ClientInterface $client = null)
    {

        throw new \Exception('method create not implemented for '.static::class);
    }
}
