<?php

namespace Test\Core;

use PHPUnit\Framework\TestCase;

class ResponseTest extends TestCase
{
    /** @test */
    public function
    set_content_and_retrieve_it()
    {
        //given
        $response = new \Almacen\Core\Response();
        $expectedContent = 'response content';
        $response->setContent($expectedContent);
        //when
        ob_start();
        $response->send();
        $content = ob_get_clean();
        //do
        $this->assertEquals($expectedContent, $content);
    }
}
