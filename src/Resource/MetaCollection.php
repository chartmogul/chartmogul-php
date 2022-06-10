<?php

namespace ChartMogul\Resource;

use Doctrine\Common\Collections\ArrayCollection;

class MetaCollection extends ArrayCollection
{
    /**
    * @var int
    */
    public $next_key;
    /**
    * @var int
    */
    public $prev_key;
    /**
    * @var int
    */
    public $page;
    /**
    * @var int
    */
    public $total_pages;
    /**
    * @var string
    */
    public $before_key;
}
