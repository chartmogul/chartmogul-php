<?php

namespace ChartMogul;

use ChartMogul\Http\ClientInterface;
use ChartMogul\Resource\AbstractResource;
use ChartMogul\Service\ShowTrait;

/**
 * @codeCoverageIgnore
 * @property-read      string $name
 * @property-read      string $currency
 * @property-read      string $time_zone
 * @property-read      string $week_start_on
 */
class Account extends AbstractResource
{
    use ShowTrait;

    /**
     * @ignore
     */
    public const RESOURCE_PATH = '/v1/account';
    /**
     * @ignore
     */
    public const RESOURCE_NAME = 'Account';
    /**
     * @ignore
     */
    public const ROOT_KEY = 'account';

    public $name;
    public $currency;
    public $time_zone;
    public $week_start_on;
}
