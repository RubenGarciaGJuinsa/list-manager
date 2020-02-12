<?php


namespace Almacen\Core\UrlParser;


abstract class Router implements EnroutableInterface
{
    protected string $namespace;
    protected string $defaultController;
    protected string $defaultAction;

    public function __construct($namespace, $defaultController, $defaultAction)
    {
        $this->namespace = $namespace;
        $this->defaultController = $defaultController;
        $this->defaultAction = $defaultAction;
    }

    /**
     * @return string
     */
    public function getDefaultController(): string
    {
        return $this->defaultController;
    }

    /**
     * @return string
     */
    public function getNamespace(): string
    {
        return $this->namespace;
    }

    /**
     * @return string
     */
    public function getDefaultAction(): string
    {
        return $this->defaultAction;
    }
}