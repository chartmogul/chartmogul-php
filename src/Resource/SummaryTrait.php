<?php

namespace ChartMogul\Resource;

use ChartMogul\Summary;

trait SummaryTrait
{
    protected $summary = [];

    protected function setSummary($summary = [])
    {
        if ($summary instanceof Summary) {
            //do nothing
        } elseif (is_array($summary)) {
            $this->summary = new Summary($summary);
        }
    }
}
