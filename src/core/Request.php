<?php


namespace Almacen\Core;


class Request
{
    public static function getGetParam($name, $default = null)
    {
        return isset($_GET[$name]) ? $_GET[$name] : $default;
    }

    public static function getPostParam($name, $default = null)
    {
        return isset($_POST[$name]) ? $_POST[$name] : $default;
    }
}