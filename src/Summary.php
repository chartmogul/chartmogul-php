<?php

namespace ChartMogul;

use ChartMogul\Resource\AbstractModel;

/**
 * @property-read string $current;
 * @property-read string $previous;
 * @property-read string $percentage_change;
 */
class Summary extends AbstractModel
{
    protected $current;
    protected $previous;
    protected $percentage_change;
}
