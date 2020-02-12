<?php

namespace Test\Core\Db;

use Almacen\Core\Db\Db;
use Test\Core\Application\ApplicationWrapper;
use Test\Core\Fake\FakeDb;
use PHPUnit\Framework\TestCase;

class DbTest extends TestCase
{
    /** @test */
    public function
    get_db_instance()
    {
        //given
        $configFiles = [
            __DIR__.'/../Application/config/main.php',
        ];
        ApplicationWrapper::delete();
        ApplicationWrapper::getInstance($configFiles);
        //when
        $db = Db::getInstance();
        //do
        $this->assertEquals(new FakeDb(), $db);
    }
}
