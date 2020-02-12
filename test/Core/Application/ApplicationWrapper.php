<?php

namespace Test\Core\Application;

use Almacen\Core\Application;
use Almacen\Core\Controller;
use Almacen\Core\UrlParser\Route;

class ApplicationWrapper extends Application
{
    public function exposedCheckIfControllerExists(string $controllerName)
    {
        $this->checkIfControllerExists($controllerName);
    }

    public function exposedCheckIfActionExists(Controller $controllerInstance, string $actionName)
    {
        $this->checkIfActionExists($controllerInstance, $actionName);
    }

    public function exposedGetControllerInstance(\Almacen\core\UrlParser\Route $route)
    {
        return $this->getControllerInstance($route);
    }

    public function exposedGetRoute()
    {
        return $this->getRoute();
    }

    public function exposedCheckRoute(?Route $route): void
    {
        $this->checkRoute($route);
    }

    public function getCurrentUrl()
    {
        return '';
    }
}