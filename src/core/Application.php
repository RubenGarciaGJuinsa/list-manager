<?php

namespace Almacen\Core;

use Kata\Controllers\ErrorController;
use Almacen\core\Db\DbInterface;
use Almacen\Core\Exception\ActionNotFoundException;
use Almacen\Core\Exception\ControllerNotFoundException;
use Almacen\Core\Exception\PageNotFoundException;
use Almacen\Core\UrlParser\EnroutableInterface;
use Almacen\Core\UrlParser\Route;

class Application
{
    private static ?self $app;
    public Response $response;
    protected string $name = '';
    protected array $routers = [];
    protected array $configFiles = [];
    protected array $db = [];
    protected DbInterface $dbInstance;

    private function __construct($configFiles = [])
    {
        $this->configFiles = $configFiles;
        $this->response = new Response();
        $this->init();
    }

    protected function init()
    {
        $this->loadConfig();
    }

    protected function loadConfig()
    {
        $config = $this->getConfigParams();

        $classVars = get_object_vars($this);

        foreach ($config as $configElemName => $configElemValue) {
            if (array_key_exists($configElemName, $classVars)) {
                $this->{$configElemName} = $configElemValue;
            }
        }
    }

    protected function getConfigParams()
    {
        $configFiles = $this->getConfigFiles();
        $config = [];

        foreach ($configFiles as $configFile) {
            $config = array_merge($config, require $configFile);
        }

        return $config;
    }

    protected function getConfigFiles()
    {
        return $this->configFiles;
    }

    public static function getInstance($configFiles = [])
    {
        if (empty(static::$app)) {
            static::$app = new static($configFiles);
        }

        return static::$app;
    }

    public static function delete()
    {
        static::$app = null;
    }

    /**
     * Translation system
     * @param string $category
     * @param string $text
     * @return string
     */
    public static function t(string $category, string $text, string $lang = '')
    {
        //TODO
        return $text;
    }

    public function getName()
    {
        return $this->name;
    }

    public function run()
    {
        $content = '';

        try {
            $route = $this->getRoute();

            $this->checkRoute($route);

            $controllerInstance = $this->getControllerInstance($route);

            $actionName = $route->getAction();
            $this->checkIfActionExists($controllerInstance, $actionName);

            $content = $this->runController($controllerInstance, $actionName, $route->getParams());
        } catch (\Exception $e) {
            $content = $this->runController(new ErrorController(), 'actionIndex', [$e]);
        }

        $this->response->setContent($content);
        $this->response->send();
    }

    /**
     * @return Route|null
     */
    protected function getRoute()
    {
        $url = $this->getCurrentUrl();

        foreach ($this->routers as $router) {
            /** @var EnroutableInterface $routerInstance */
            $routerInstance = new $router['class'](
                $router['defaultNamespace'],
                $router['defaultController'],
                $router['defaultAction']
            );
            $controllerAction = $routerInstance->parseUrl($url);
            if ( ! empty($controllerAction)) {
                return $controllerAction;
            }
        }

        return null;
    }

    protected function getCurrentUrl()
    {
        return Request::getGetParam('path', '');
    }

    /**
     * @param Route|null $route
     * @throws PageNotFoundException
     */
    protected function checkRoute(?Route $route): void
    {
        if (empty($route)) {
            throw new PageNotFoundException();
        }
    }

    /**
     * @param Route|null $route
     * @return mixed
     * @throws ControllerNotFoundException
     */
    protected function getControllerInstance(Route $route)
    {
        $controllerName = $route->getController();
        $this->checkIfControllerExists($controllerName);

        return new $controllerName();
    }

    /**
     * @param string $controllerName
     * @throws ControllerNotFoundException
     */
    protected function checkIfControllerExists(string $controllerName): void
    {
        if ( ! class_exists($controllerName)) {
            throw new ControllerNotFoundException();
        }
    }

    /**
     * @param Controller $controllerInstance
     * @param string $actionName
     * @throws ActionNotFoundException
     */
    protected function checkIfActionExists(Controller $controllerInstance, string $actionName): void
    {
        if ( ! is_callable([$controllerInstance, $actionName])) {
            throw new ActionNotFoundException('Action "'.$actionName.'" not found');
        }
    }

    /**
     * @param $controllerInstance
     * @param string $actionName
     * @param array $params
     * @return mixed
     */
    protected function runController(
        $controllerInstance,
        string $actionName,
        array $params = []
    ) {
        return call_user_func_array([$controllerInstance, 'run'], [$actionName, $params]);
    }

    public function getBasePath()
    {
        return realpath(__DIR__.'/../../');
    }

    public function initDb()
    {
        if (empty($this->dbInstance)) {
            $dbClass = $this->db['class'];
            $this->dbInstance = $dbClass::init($this->db);
        }

        return $this->dbInstance;
    }
}