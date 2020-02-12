<?php

namespace Test\Core\Routers;

use Almacen\Core\UrlParser\DefaultRouter;
use Almacen\Core\UrlParser\Route;
use Almacen\Core\UrlParser\Router;
use PHPUnit\Framework\TestCase;

class DefaultRouterTest extends TestCase
{
    protected Router $router;

    public function setUp(): void
    {
        $this->router = new DefaultRouter(
            'Test\Controllers',
            'SiteController',
            'actionIndex'
        );
        parent::setUp();
    }

    /** @test */
    public function
    given_empty_url()
    {
        $url = '';
        $route = $this->router->parseUrl($url);

        $expectedRoute = new Route('Test\Controllers\SiteController', 'actionIndex');
        $this->assertEquals($expectedRoute, $route);
    }


    /** @test */
    public function
    given_valid_url()
    {
        $url = 'example/create';
        $route = $this->router->parseUrl($url);

        $expectedRoute = new Route('Test\Controllers\ExampleController', 'actionCreate');
        $this->assertEquals($expectedRoute, $route);
    }
}