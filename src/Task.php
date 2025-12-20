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
 * @property-read string $task_details
 * @property-read string $assignee
 * @property-read string $due_date
 * @property-read string|null $completed_at
 * @property-read string $created_at
 * @property-read string $updated_at
 */
class Task extends AbstractResource
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
    public const RESOURCE_NAME = 'Task';
    /**
     * @ignore
     */
    public const RESOURCE_PATH = '/v1/tasks';
    public const RESOURCE_ID = 'uuid';
    public const ROOT_KEY = 'entries';

    protected $uuid;
    protected $customer_uuid;
    protected $task_details;
    protected $assignee;
    protected $due_date;
    protected $completed_at;
    protected $created_at;
    protected $updated_at;

    public function __construct(array $attr = [], ?ClientInterface $client = null)
    {
        if (isset($attr['task_uuid'])) {
            $attr['uuid'] = $attr['task_uuid'];
        }
        parent::__construct($attr);
    }
}
