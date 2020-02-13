<?php

namespace Test\Core\Fake;

use Almacen\Core\Application;
use Almacen\Core\Db\DbInterface;

class FakeDb implements DbInterface
{

    public static function init(array $config): DbInterface
    {
        return new static();
    }

    public static function getInstance(): DbInterface
    {
        return new static();
    }
}