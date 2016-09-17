<?php

namespace ChartMogul\Resource;

use \Doctrine\Common\Collections\ArrayCollection;

abstract class AbstractModel
{
    /**
    * @codeCoverageIgnore
    */
    public function __construct(array $data = [])
    {
        foreach ($data as $key => $value) {
            // replace property names with dash with underscores
            $key = str_replace('-', '_', $key);
            $this->$key = $value;
        }
    }


    private function objectToArray($obj)
    {
        if ($obj instanceof ArrayCollection) {
            $obj = $obj->toArray();
        }

        $data = is_object($obj)? get_object_vars($obj): $obj;

        if (is_array($data)) {
            return array_filter(
                array_map([$this, 'objectToArray'], $data),
                function ($item) {
                    return !is_null($item);
                }
            );
        }

        return $data;
    }
    /**
    * @codeCoverageIgnore
    */
    public function toArray()
    {
        return $this->objectToArray($this);
    }

    /**
     * @param array $data
     * @return self
     */
    public static function fromArray(array $data)
    {
        return new static($data);
    }

    /**
    * @ignore
    * @codeCoverageIgnore
    */
    public function __get($name)
    {
        if (isset($this->$name)) {
            return $this->$name;
        }
        return null;
    }
}
