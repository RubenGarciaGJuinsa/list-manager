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
            ->enableOriginalConstructor()
            ->onlyMethods(['select', 'connect'])
            ->getMock();

        $dbMock->method('select')
            ->with('list')
            ->willReturn([]);

        $dbMock->expects($this->once())
            ->method('connect');

        $listManager = new ListManager($dbMock);
        $lists = $listManager->getLists();
        $this->assertEquals([], $lists);
    }

    /** @test */
    public function
    create_new_list_with_name()
    {
        $listName = 'ToDo';

        $dbMock = $this->getMockBuilder(Database::class)
            ->enableOriginalConstructor()
            ->onlyMethods(['insert'])
            ->getMock();

        $dbMock->expects($this->once())
            ->method('insert')
            ->with('list', ['name' => $listName]);

        $listManager = new ListManager($dbMock);
        $listManager->createList($listName);
    }
}