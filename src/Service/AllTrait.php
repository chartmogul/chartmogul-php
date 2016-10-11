<?php
namespace ChartMogul\Service;

use ChartMogul\Http\ClientInterface;

/**
* @codeCoverageIgnore
*/
trait AllTrait
{

    /**
     * Returns a list of objects
     * @param  array $data
     * @param  ClientInterface|null $client 0
     * @return \Doctrine\Common\Collections\ArrayCollection|self

     */
    public static function all(array $data = [], ClientInterface $client = null)
    {

        return (new RequestService($client))
            ->setResourceClass(static::class)
            ->all($data);
    }
}
