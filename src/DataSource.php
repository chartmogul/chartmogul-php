<?php

namespace ChartMogul;

use ChartMogul\Http\ClientInterface;
use ChartMogul\Resource\AbstractResource;
use ChartMogul\Service\CreateTrait;
use ChartMogul\Service\AllTrait;
use ChartMogul\Service\DestroyTrait;
use ChartMogul\Service\GetTrait;

/**
 * @codeCoverageIgnore
 * @property-read      string $uuid
 * @property-read      string $status
 * @property-read      string $created_at
 * @property-read      string $system
 * @property-read      array  $processing_status
 * @property-read      array  $auto_churn_subscription_setting
 * @property-read      array  $invoice_handling_setting
 */
class DataSource extends AbstractResource
{
    use CreateTrait;
    use AllTrait;
    use DestroyTrait;
    use GetTrait;

    /**
     * @ignore
     */
    public const RESOURCE_PATH = '/v1/data_sources';

    /**
     * @ignore
     */
    public const RESOURCE_NAME = 'Data Source';

    /**
     * @ignore
     */
    public const ROOT_KEY = 'data_sources';

    protected $uuid;
    protected $status;
    protected $created_at;
    protected $system;
    protected $processing_status;
    protected $auto_churn_subscription_setting;
    protected $invoice_handling_setting;

    public $name;
}
