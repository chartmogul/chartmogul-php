<?php

namespace ChartMogul\Metrics;

use ChartMogul\Resource\AbstractResource;
use ChartMogul\Http\ClientInterface;
use ChartMogul\Service\CreateTrait;
use ChartMogul\Service\GetTrait;

/**
 * @property-read string $id;
 * @property-read string $status;
 * @property-read string $file_url;
 * @property-read string $params;
 * @property-read string $expires_at;
 * @property-read string $created_at;
 */
class ActivitiesExport extends AbstractResource
{
    use CreateTrait;
    use GetTrait;

    /**
     * @ignore
     */
    public const RESOURCE_NAME = 'ActivitiesExport';
    /**
     * @ignore
     */
    public const RESOURCE_PATH = '/v1/activities_export';
    public const RESOURCE_ID = 'activities_export_uuid';
    public const ROOT_KEY = null;

    protected $id;
    protected $status;
    protected $file_url;
    protected $params;
    protected $expires_at;
    protected $created_at;

    protected $activity_type;
    protected $start_date;
    protected $end_date;
}
