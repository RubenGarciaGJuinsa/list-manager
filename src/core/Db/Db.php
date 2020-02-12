<?php


namespace Almacen\Core\Db;


use Almacen\Core\Application;

class Db
{

    private static $instance;

    public static function getInstance()
    {
        if (empty(static::$instance)) {
            static::$instance = Application::getInstance()->initDb();
        }

        return static::$instance;
    }
}