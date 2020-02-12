<?php


namespace Almacen\Core\UrlParser;


class DefaultRouter extends Router
{

    public function parseUrl(string $url): ?Route
    {
        $urlSlices = explode('/', $url);

        $controllerName = array_shift($urlSlices);
        $controller = $this->parseControllerName($controllerName);

        $actionName = array_shift($urlSlices);
        $action = $this->parseActionName($actionName);

        $params = $urlSlices;

        return new Route($controller, $action, $params);
    }

    protected function parseControllerName($controllerName)
    {
        $controllerClass = $this->getNamespace().'\\';

        if ( ! empty($controllerName)) {
            $parts = explode('_', $controllerName);
            $parts[] = 'Controller';
            $parts = array_map('ucfirst', $parts);
            $controllerClass .= implode('', $parts);
        } else {
            $controllerClass .= $this->getDefaultController();
        }

        return $controllerClass;
    }

    protected function parseActionName($actionName)
    {
        $actionFunction = $this->getDefaultAction();

        if ( ! empty($actionName)) {
            $parts = explode('_', $actionName);
            $parts = array_map('ucfirst', $parts);
            $parts = array_merge(['action'], $parts);
            $actionFunction = implode('', $parts);
        }

        return $actionFunction;
    }
}