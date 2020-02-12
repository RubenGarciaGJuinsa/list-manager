<?php


namespace Test;


use Kata\Database;
use Kata\TaskManager;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

class TaskManagerTest extends TestCase
{
    protected MockObject $dbMock;

    public function setUp(): void
    {
        $this->dbMock = $this->getMockBuilder(Database::class)
            ->enableOriginalConstructor()
            ->onlyMethods(['select', 'connect', 'insert'])
            ->getMock();
    }

    /** @test */
    public function
    get_empty_tasks_from_list()
    {
        $this->dbMock->method('select')
            ->with('task', ['list_id' => 1])
            ->willReturn([]);

        $taskManager = new TaskManager($this->dbMock);
        $tasks = $taskManager->getTasksFromList(1);

        $this->assertEquals([], $tasks);
    }

    /** @test */
    public function
    create_new_task()
    {
        $taskName = 'taskName';
        $this->dbMock->method('select')
            ->withConsecutive(
                ['task', ['list_id' => 1]],
                ['task', ['list_id' => 1, 'name' => $taskName]],
                ['task', ['list_id' => 1]]
            )
            ->willReturnOnConsecutiveCalls(
                [],
                [],
                [['id' => 1, 'list_id' => 1, 'name' => $taskName]]
            );

        $this->dbMock->expects($this->once())
            ->method('insert')
            ->with('task', ['name' => $taskName, 'list_id' => 1]);

        $taskManager = new TaskManager($this->dbMock);
        $tasks = $taskManager->getTasksFromList(1);
        $this->assertEquals([], $tasks);

        $taskManager->createNewTask($taskName, 1);

        $tasks = $taskManager->getTasksFromList(1);
        $this->assertEquals([['id' => 1, 'list_id' => 1, 'name' => $taskName]], $tasks);
    }

    /** @test */
    public function
    create_new_task_with_existing_name_expects_exception()
    {
        $taskName = 'taskName';
        $this->dbMock->method('select')
            ->with('task', ['name' => $taskName, 'list_id' => 1])
            ->willReturn(
                [
                    ['id' => 1, 'list_id' => 1, 'name' => $taskName],
                ]
            );

        $this->dbMock->expects($this->once())
            ->method('select')
            ->with('task', ['name' => $taskName, 'list_id' => 1]);

        $this->dbMock->expects($this->never())
            ->method('insert');

        $this->expectExceptionMessage('Another task is created with the same name in the same list');

        $taskManager = new TaskManager($this->dbMock);
        $taskManager->createNewTask($taskName, 1);
    }
}