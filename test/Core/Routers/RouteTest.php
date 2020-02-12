<?php


namespace Test\Core\Routers;


use Almacen\core\UrlParser\Route;
use PHPUnit\Framework\TestCase;

class RouteTest extends TestCase
{
    /** @test */
    public function
    initialize_and_get_controller_action_params()
    {
        //given
        $controller = 'Controller';
        $action = 'Action';
        $params = [
            'param1',
            'param2',
            'param3',
        ];
        $route = new Route($controller, $action, $params);
        //when
        $givenController = $route->getController();
        $givenAction = $route->getAction();
        $givenParams = $route->getParams();
        //do
        $this->assertEquals($controller, $givenController);
        $this->assertEquals($action, $givenAction);
        $this->assertEquals($params, $givenParams);
    }
}