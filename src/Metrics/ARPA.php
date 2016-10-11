<?php

namespace ChartMogul\Metrics;

use ChartMogul\Resource\AbstractModel;

/**
 * @property-read string $data
 * @property-read string $arpa
 */
class ARPA extends AbstractModel
{
    protected $date;
    protected $arpa;
}
