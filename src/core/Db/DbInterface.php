<?php

namespace Almacen\Core\Db;

interface DbInterface
{
    public static function init(array $config, $force = false): self;

    public static function getInstance(): DbInterface;
}