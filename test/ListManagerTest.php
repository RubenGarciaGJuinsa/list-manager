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
            ->onlyMethods(['insert', 'select'])
            ->getMock();

        $dbMock->method('select')
            ->with('list')
            ->willReturn([]);

        $dbMock->expects($this->once())
            ->method('insert')
            ->with('list', ['name' => $listName]);

        $listManager = new ListManager($dbMock);
        $listManager->createList($listName);
    }

    /** @test */
    public function
    create_new_list_with_existing_name_and_get_exception()
    {
        $listName = 'ToDo';

        $dbMock = $this->getMockBuilder(Database::class)
            ->enableOriginalConstructor()
            ->onlyMethods(['insert', 'select'])
            ->getMock();

        $dbMock->method('select')
            ->with('list', ['name' => $listName])
            ->willReturn([['id' => 1, 'name' => $listName]]);

        $dbMock->expects($this->never())
            ->method('insert');

        $this->expectExceptionMessage('Another list is created with the same name');

        $listManager = new ListManager($dbMock);
        $listManager->createList($listName);
    }
}