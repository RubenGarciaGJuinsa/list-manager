<?php

namespace Test\Core;

use Test\Core\Fake\FakeBaseObjectImplementation;
use PHPUnit\Framework\TestCase;

class BaseObjectTest extends TestCase
{
    /** @test */
    public function
    hidratate_object()
    {
        //given
        $params = [
            'param1' => 1,
            'param3' => 3,
        ];
        //when
        $object = new FakeBaseObjectImplementation($params);
        //do
        $this->assertEquals($params['param1'], $object->param1);
        $this->assertEmpty($object->param2);
        $this->assertEquals($params['param3'], $object->param3);

        $object->hydrate(['param2' => 2, 'param3' => 4]);

        $this->assertEquals(2, $object->param2);
        $this->assertEquals(4, $object->param3);
    }
}