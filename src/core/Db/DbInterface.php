<?php

namespace Almacen\Core\Db;

interface DbInterface
{
    public static function init(array $config): self;

    public static function getInstance(): DbInterface;
}