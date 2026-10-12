<?php

namespace ChartMogul;

use ChartMogul\Resource\AbstractResource;
use ChartMogul\Resource\Collection;
use ChartMogul\Service\AllTrait;
use ChartMogul\Service\CreateTrait;
use ChartMogul\Service\UpdateTrait;
use ChartMogul\Service\DestroyTrait;
use ChartMogul\Service\GetTrait;
use ChartMogul\Service\FromArrayTrait;
use ChartMogul\Http\ClientInterface;

/**
 * @property-read string $uuid
 * @property-read string $customer_uuid
 * @property-read string $type
 * @property-read string $text
 * @property-read integer $call_duration
 * @property-read string $author
 * @property-read string|null $author_email
 * @property-read string $created_at
 * @property-read string $updated_at
 * @deprecated Use ChartMogul\EntityNote (the /v1/notes API) instead.
 */
class CustomerNote extends AbstractResource
{
    use AllTrait {
        AllTrait::all as private traitAll;
    }
    use CreateTrait {
        CreateTrait::create as private traitCreate;
    }
    use GetTrait {
        GetTrait::retrieve as private traitRetrieve;
    }
    use DestroyTrait {
        DestroyTrait::destroy as private traitDestroy;
    }
    use UpdateTrait {
        UpdateTrait::update as private traitUpdate;
    }
    use FromArrayTrait;

    /**
     * @ignore
     */
    public const RESOURCE_NAME = 'CustomerNote';
    /**
     * @ignore
     */
    public const RESOURCE_PATH = '/v1/customer_notes';
    public const RESOURCE_ID = 'note_uuid';
    public const ROOT_KEY = 'entries';

    protected $uuid;

    protected $author;
    protected $author_email;
    protected $call_duration;
    protected $created_at;
    protected $customer_uuid;
    protected $text;
    protected $type;
    protected $updated_at;

    /**
     * Returns a list of objects
     *
     * @param  array                $data
     * @param  ClientInterface|null $client
     * @return Collection|self[]|self
     * @deprecated Use EntityNote::all() instead
     */
    public static function all(array $data = [], ?ClientInterface $client = null)
    {
        @trigger_error(
            'CustomerNote::all() is deprecated. Use EntityNote::all() instead.',
            E_USER_DEPRECATED
        );
        return self::traitAll($data, $client);
    }

    /**
     * Create a Resource
     *
     * @param  array                $data
     * @param  ClientInterface|null $client
     * @return self
     * @deprecated Use EntityNote::create() instead
     */
    public static function create(array $data = [], ?ClientInterface $client = null)
    {
        @trigger_error(
            'CustomerNote::create() is deprecated. Use EntityNote::create() instead.',
            E_USER_DEPRECATED
        );
        return self::traitCreate($data, $client);
    }

    /**
     * Get a single resource by UUID.
     *
     * @return self
     * @deprecated Use EntityNote::retrieve() instead
     */
    public static function retrieve($uuid, ?ClientInterface $client = null, array $query = [])
    {
        @trigger_error(
            'CustomerNote::retrieve() is deprecated. Use EntityNote::retrieve() instead.',
            E_USER_DEPRECATED
        );
        return self::traitRetrieve($uuid, $client, $query);
    }

    /**
     * Update a Resource
     *
     * @param  array                $id
     * @param  array                $data
     * @param  ClientInterface|null $client
     * @return self
     * @deprecated Use EntityNote::update() instead
     */
    public static function update(array $id = [], array $data = [], ?ClientInterface $client = null)
    {
        @trigger_error(
            'CustomerNote::update() is deprecated. Use EntityNote::update() instead.',
            E_USER_DEPRECATED
        );
        return self::traitUpdate($id, $data, $client);
    }

    /**
     * Delete a resource
     *
     * @return boolean
     * @deprecated Use EntityNote->destroy() instead
     */
    public function destroy()
    {
        @trigger_error(
            'CustomerNote->destroy() is deprecated. Use EntityNote->destroy() instead.',
            E_USER_DEPRECATED
        );
        return $this->traitDestroy();
    }
}
