<?php
namespace Test;

use Kata\Database;
use Kata\ListManager;
use PHPUnit\Framework\TestCase;

class ListManagerTest extends TestCase
{
    /** @test */
    public function
    get_empty_lists()
    {
        $dbMock = $this->getMockBuilder(Database::class)
            ->onlyMethods(['getLists', 'connect'])
            ->getMock();

        $dbMock->method('getLists')
            ->willReturn([]);

        $dbMock->expects($this->once())
            ->method('connect');

        $listManager = new ListManager();
        $lists = $listManager->getLists();
        $this->assertEquals([], $lists);
    }
}