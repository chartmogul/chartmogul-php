<?php

namespace ChartMogul;

use ChartMogul\Resource\AbstractModel;

class Summary extends AbstractModel {
    protected $current;
    protected $previous;
    protected $percentage_change;
}