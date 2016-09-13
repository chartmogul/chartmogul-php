<?php

namespace ChartMogul\Resource;

abstract class AbstractModel
{
    /**
    * @codeCoverageIgnore
    */
    public function __construct(array $data = [])
    {
        foreach ($data as $key => $value) {
            $this->$key = $value;
        }
    }


    private function objectToArray($obj)
    {
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
