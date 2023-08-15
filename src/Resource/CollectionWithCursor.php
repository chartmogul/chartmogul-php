<?php

namespace ChartMogul\Resource;

use Doctrine\Common\Collections\ArrayCollection;

class CollectionWithCursor extends ArrayCollection
{
    /**
    * @var boolean
    */
    public $has_more;
    /**
    * @var string
    */
    public $cursor;
    /**
    * @var string
    */
    public $customer_uuid;
}
