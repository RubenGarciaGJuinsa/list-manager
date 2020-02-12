<?php

namespace Test\Core;

use Almacen\Core\Application;
use Almacen\Core\Controller;
use Almacen\Core\Exception\ActionNotFoundException;
use Almacen\Core\Exception\ControllerNotFoundException;
use Almacen\Core\UrlParser\Route;
use Almacen\Core\UrlParser\Router;
use Test\Core\Application\ApplicationWrapper;
use Test\Core\Fake\FakeDb;
use PHPUnit\Framework\TestCase;

class ApplicationTest extends TestCase
{
    protected function
    tearDown(): void
    {
        ApplicationWrapper::delete();
    }

    /** @test */
    public function
    given_an_app_name_in_config_store_it()
    {
        //given
        $configFiles = [
            __DIR__.'/config/main.php',
        ];
        $app = ApplicationWrapper::getInstance($configFiles);
        //when
        $name = $app->getName();
        //do
        $this->assertEquals('App', $name);
    }

    /** @test */
    public function
    given_an_app_name_and_more_than_one_config_file_take_the_last()
    {
        //given
        $configFiles = [
            __DIR__.'/config/main.php',
            __DIR__.'/config/alternative.php',
        ];
        $app = ApplicationWrapper::getInstance($configFiles);
        //when
        $name = $app->getName();
        //do
        $this->assertEquals('Alternative app', $name);
    }

    /** @test */
    public function
    given_valid_controller_not_throws_exception()
    {
        //given
        $app = ApplicationWrapper::getInstance();
        //when
        try {
            $app->exposedCheckIfControllerExists('Test\Core\EmptyController');
            $this->assertTrue(true, 'Valid controller name');
        } catch (ControllerNotFoundException $e) {
            $this->fail('Exception ControllerNotFoundException thrown');
        }
    }

    /** @test */
    public function
    given_not_valid_controller_throws_exception()
    {
        //given
        $app = ApplicationWrapper::getInstance();
        $this->expectException('Almacen\Core\Exception\ControllerNotFoundException');
        //when
        $app->exposedCheckIfControllerExists('FakeNamespace/FakeController');
    }

    /** @test */
    public function
    given_valid_action_not_throws_exception()
    {
        //given
        $app = ApplicationWrapper::getInstance();
        $controllerInstance = new EmptyController();
        //when
        try {
            $app->exposedCheckIfActionExists($controllerInstance, 'actionTest');
            $this->assertTrue(true, 'Valid action name');
        } catch (ActionNotFoundException $e) {
            $this->fail('Exception ActionNotFoundException thrown');
        }
    }

    /** @test */
    public function
    given_not_valid_action_throws_exception()
    {
        //given
        $app = ApplicationWrapper::getInstance();
        $controllerInstance = new EmptyController();
        $this->expectException('Almacen\Core\Exception\ActionNotFoundException');
        //when
        $app->exposedCheckIfActionExists($controllerInstance, 'actionFake');
    }

    /** @test */
    public function
    given_a_route_get_controller_instance()
    {
        //given
        $route = new Route('Test\Core\EmptyController', 'actionTest');
        $app = ApplicationWrapper::getInstance();
        //when
        $controller = $app->exposedGetControllerInstance($route);
        //do
        $expectedController = new EmptyController();
        $this->assertEquals($expectedController, $controller);
    }

    /** @test */
    public function
    get_route()
    {
        //given
        $configFiles = [
            __DIR__.'/config/main.php',
        ];
        $app = ApplicationWrapper::getInstance($configFiles);
        $expectedRoute = new Route('Test\Core\EmptyController', 'actionTest');
        //when
        $route = $app->exposedGetRoute();
        //do
        $this->assertEquals($expectedRoute, $route);
    }

    /** @test */
    public function
    run_application()
    {
        //given
        $configFiles = [
            __DIR__.'/config/main.php',
        ];
        $app = ApplicationWrapper::getInstance($configFiles);

        //when
        ob_start();
        $app->run();
        $content = ob_get_clean();
        //do
        $this->assertEquals('123', $content);
    }

    /** @test */
    public function
    initialize_database()
    {
        //given
        $configFiles = [
            __DIR__.'/config/main.php',
        ];
        $app = ApplicationWrapper::getInstance($configFiles);
        //when
        $db = $app->initDb();
        //do
        $this->assertEquals(new FakeDb(), $db);
    }

    /** @test */
    public function
    given_empty_route_throw_exception()
    {
        $app = ApplicationWrapper::getInstance();
        $this->expectException('Almacen\Core\Exception\PageNotFoundException');

        $app->exposedCheckRoute(null);
        $this->fail('Exception not thrown');
    }

    /** @test */
    public function
    get_base_path()
    {
        $app = Application::getInstance();
        $expectedRoute = realpath(__DIR__.'/../../../');

        $route = $app->getBasePath();
        $this->assertEquals($expectedRoute, $route);
    }
}

class EmptyController extends Controller
{
    public function actionTest()
    {
        return '123';
    }
}

class FakeRouter extends Router
{

    public function parseUrl(string $url): ?Route
    {
        return new Route('Test\Core\EmptyController', 'actionTest');
    }
}