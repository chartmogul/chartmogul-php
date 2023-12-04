<?php

namespace ChartMogul;

use ChartMogul\Resource\AbstractResource;
use ChartMogul\Resource\CollectionWithCursor;
use ChartMogul\Http\ClientInterface;
use ChartMogul\Service\AllTrait;
use ChartMogul\Service\UpdateTrait;
use ChartMogul\Service\CreateTrait;
use ChartMogul\Service\DestroyTrait;
use ChartMogul\Service\GetTrait;

/**
 * @property-read string $uuid
 * @property-read string $customer_uuid
 * @property-read string $type
 * @property-read string $text
 * @property-read integer $call_duration
 * @property-read string $author
 * @property-read string $created_at
 * @property-read string $updated_at
 */
class CustomerNote extends AbstractResource
{
    use CreateTrait;
    use AllTrait;
    use GetTrait;
    use DestroyTrait;
    use UpdateTrait;

    /**
     * @ignore
     */
    public const RESOURCE_NAME = 'CustomerNote';
    /**
     * @ignore
     */
    public const RESOURCE_PATH = '/v1/customers/:customer_uuid/notes';
    public const RESOURCE_ID = 'note_uuid';
    public const ROOT_KEY = 'entries';

    protected $uuid;
    protected $customer_uuid;
    protected $type;
    protected $text;
    protected $call_duration;
    protected $author;
    protected $created_at;
    protected $updated_at;

    /**
     * Overrides fromArray so that it will return a collection with cursor instead.
     *
     * @param  array                $data
     * @param  ClientInterface|null $client
     * @return CollectionWithCursor|static
     */
    public static function fromArray(array $data, ClientInterface $client = null)
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
