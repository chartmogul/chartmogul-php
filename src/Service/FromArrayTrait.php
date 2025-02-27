<?php

namespace ChartMogul\Service;

use ChartMogul\Resource\CollectionWithCursor;
use ChartMogul\Http\ClientInterface;

/**
* @codeCoverageIgnore
*/
trait FromArrayTrait
{
    /**
     * Overrides fromArray so that it will return a collection with cursor instead.
     *
     * @param  array                $data
     * @param  ClientInterface|null $client
     * @return CollectionWithCursor|static
     */
    public static function fromArray(array $data, ?ClientInterface $client = null)
    {
        if (isset($data[static::ROOT_KEY])) {
            $array = new CollectionWithCursor(
                array_map(
                    function ($data) use ($client) {
                        return static::fromArray($data, $client);
                    },
                    $data[static::ROOT_KEY]
                )
            );

            if (isset($data['cursor'])) {
                $array->cursor = $data['cursor'];
            }

            if (isset($data['has_more'])) {
                $array->has_more = $data['has_more'];
            }

            return $array;
        }

        return new static($data, $client);
    }
}
