<?php


namespace Almacen\Core\Db;


use Almacen\Core\Application;

class Db
{
    public static function getInstance()
    {
        return Application::getInstance()->initDb();
    }
}