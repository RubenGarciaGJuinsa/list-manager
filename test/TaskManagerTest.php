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
}