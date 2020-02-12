<?php


namespace Almacen\Core;


abstract class BaseObject
{
    public function __construct($data = [])
    {
        if ( ! empty($data)) {
            $this->hydrate($data);
        }
    }

    public function hydrate($data)
    {
        foreach ($data as $name => $value) {
            $method = 'set'.str_replace(' ', '', ucwords(str_replace('_', ' ', $name)));
            if (is_callable([$this, $method])) {
                $this->$method($value);
            }
        }
    }
}