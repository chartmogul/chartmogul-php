<?php

namespace ChartMogul\Resource;

use Doctrine\Common\Collections\ArrayCollection;

class SubscriptionEventCollection extends ArrayCollection
{
    /**
     * @array
     */
    public $meta;
    /**
     * @var int
     */
    public $has_more;
    /**
     * @var string
     */
    public $cursor;
}
