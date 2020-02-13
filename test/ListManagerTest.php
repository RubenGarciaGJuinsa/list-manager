<?php
namespace Test;

use Almacen\Core\Application;
use Kata\Database;
use Kata\ListManager;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

class ListManagerTest extends TestCase
{
    protected MockObject $dbMock;

    public function setUp(): void
    {
        $configFiles = [
            __DIR__.'/Core/Application/config/main_sqlite.php',
        ];
        Application::delete();
        Application::getInstance($configFiles);
        Database::getInstance();

        $this->dbMock = $this->getMockBuilder(Database::class)
            ->enableOriginalConstructor()
            ->onlyMethods(['select', 'connect', 'insert'])
            ->getMock();
    }

    /** @test */
    public function
    get_empty_lists()
    {
        $this->dbMock->method('select')
            ->with('list')
            ->willReturn([]);

        $listManager = new ListManager($this->dbMock);
        $lists = $listManager->getLists();
        $this->assertEquals([], $lists);
    }

    /** @test */
    public function
    create_new_list_with_name()
    {
        $listName = 'ToDo';

        $this->dbMock->method('select')
            ->with('list')
            ->willReturn([]);

        $this->dbMock->expects($this->once())
            ->method('insert')
            ->with('list', ['name' => $listName]);

        $listManager = new ListManager($this->dbMock);
        $listManager->createList($listName);
    }

    /** @test */
    public function
    create_new_list_with_existing_name_and_get_exception()
    {
        $listName = 'ToDo';

        $this->dbMock->method('select')
            ->with('list', ['name' => $listName])
            ->willReturn([['id' => 1, 'name' => $listName]]);

        $this->dbMock->expects($this->never())
            ->method('insert');

        $this->expectExceptionMessage('Another list is created with the same name');

        $listManager = new ListManager($this->dbMock);
        $listManager->createList($listName);
    }
}