<?php

namespace ChartMogul;

use ChartMogul\Http\ClientInterface;
use ChartMogul\Resource\AbstractResource;
use ChartMogul\Service\CreateTrait;
use ChartMogul\Service\AllTrait;
use ChartMogul\Service\DestroyTrait;
use ChartMogul\Service\RequestService;

/**
 * @codeCoverageIgnore
 * @property-read      string $uuid
 * @property-read      string $status
 * @property-read      string $created_at
 * @property-read      string $system
 * @property-read      string $processing_status
 * @property-read      string $auto_churn_subscription_setting
 * @property-read      string $invoice_handling_setting
 */
class DataSource extends AbstractResource
{
    use CreateTrait;
    use AllTrait;
    use DestroyTrait;

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

    public static function retrieve($uuid, $query = [], ?ClientInterface $client = null)
    {
        return (new RequestService($client))
            ->setResourceClass(static::class)
            ->getWithQuery($uuid, $query);
    }

    public static function get($uuid, $query = [], ?ClientInterface $client = null)
    {
        return static::retrieve($uuid, $query, $client);
    }
}
