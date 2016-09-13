<?php

namespace ChartMogul\Resource;

use ChartMogul\Http\ClientInterface;

/**
* @codeCoverageIgnore
*/
trait noAllTrait
{

    public static function all(array $data = [], ClientInterface $client = null)
    {

        throw new \Exception('method all not implemented for '.static::class);
    }
}
