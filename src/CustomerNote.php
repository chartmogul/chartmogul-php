<?php

namespace ChartMogul;

use ChartMogul\Resource\AbstractResource;
use ChartMogul\Service\AllTrait;
use ChartMogul\Service\CreateTrait;
use ChartMogul\Service\UpdateTrait;
use ChartMogul\Service\DestroyTrait;
use ChartMogul\Service\GetTrait;
use ChartMogul\Service\FromArrayTrait;

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
    use AllTrait;
    use CreateTrait;
    use GetTrait;
    use DestroyTrait;
    use UpdateTrait;
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
}
