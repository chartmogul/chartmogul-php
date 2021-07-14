<?php

namespace ChartMogul\Resource;

use Doctrine\Common\Collections\ArrayCollection;

trait EntryTrait
{
    protected $entries = [];

    protected function setEntries(array $entries = [])
    {
        $this->entries = new ArrayCollection($entries);

        $entryClass = static::getEntryClass();

        foreach ($this->entries as $key => $item) {
            if ($item instanceof $entryClass) {
                //do nothing
            } elseif (is_array($item)) {
                $this->entries[$key] = new $entryClass($item);
            }
        }
    }
}
