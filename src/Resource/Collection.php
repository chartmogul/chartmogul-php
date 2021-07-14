<?php

namespace ChartMogul\Resource;

use Doctrine\Common\Collections\ArrayCollection;

class Collection extends ArrayCollection
{
    /**
    * @var int
    */
    public $current_page;
    /**
    * @var int
    */
    public $per_page;
    /**
    * @var int
    */
    public $page;
    /**
    * @var int
    */
    public $has_more;
    /**
    * @var int
    */
    public $total_pages;
    /**
    * @var string
    */
    public $customer_uuid;
}
