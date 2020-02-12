<?php

namespace Test\Core\Fake;

use Almacen\Core\Controller;

class FakeController extends Controller
{
    public function actionIndex($param1 = '', $param2 = '', $param3 = '')
    {
        return 'returnValue'.$param1.$param2.$param3;
    }

    public function getLayout()
    {
        return $this->layout;
    }

    public function exposedRender(string $viewName, $params = []): string
    {
        return $this->render($viewName, $params);
    }
}