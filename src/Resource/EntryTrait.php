<?php

namespace ChartMogul\Resource;

use ChartMogul\Resource\CollectionWithCursor;

trait EntryTrait
{
    protected $entries = [];

    protected function setEntries(array $entries = [])
    {
        $entryClass = static::getEntryClass();

        foreach ($this->entries as $key => $item) {
            if ($item instanceof $entryClass) {
                $this->entries[$key] = $item;
            } elseif (is_array($item)) {
                $this->entries[$key] = new $entryClass($item);
            }
        }
    }
}
