<?php


namespace Test\Core\Fake;


use Almacen\Core\BaseObject;

class FakeBaseObjectImplementation extends BaseObject
{
    public $param1;
    public $param2;
    public $param3;

    /**
     * @return mixed
     */
    public function getParam1()
    {
        return $this->param1;
    }

    /**
     * @param mixed $param1
     */
    public function setParam1($param1): void
    {
        $this->param1 = $param1;
    }

    /**
     * @return mixed
     */
    public function getParam2()
    {
        return $this->param2;
    }

    /**
     * @param mixed $param2
     */
    public function setParam2($param2): void
    {
        $this->param2 = $param2;
    }

    /**
     * @return mixed
     */
    public function getParam3()
    {
        return $this->param3;
    }

    /**
     * @param mixed $param3
     */
    public function setParam3($param3): void
    {
        $this->param3 = $param3;
    }
}