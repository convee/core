<?php

namespace Convee\Core;

abstract class Data
{
    public function __construct($data = null)
    {
        $objectData = (array)$data + get_object_vars($this);
        $this->set($objectData);
    }

    public static function get($class, $data = null)
    {
        return new $class($data);
    }

    public function set($key, $value = null)
    {
        if (is_array($key)) {
            foreach ($key as $name => $value) {
                if (property_exists($this, $name)) {
                    $this->$name = $this->convertValueByDataType($value, $this->$name);
                }
            }
        } else {
            if (property_exists($this, $key)) {
                $this->$key = $this->convertValueByDataType($value, $this->$key);
            }
        }

        return $this;
    }

    public function toArray()
    {
        return (array)$this;
    }

    private function convertValueByDataType($value, $dataType)
    {
        if (is_int($dataType)) {
            return (int)$value;
        } elseif (is_string($dataType)) {
            return (string)$value;
        } elseif (is_float($dataType)) {
            return (float)$value;
        }
        return $value;
    }
}