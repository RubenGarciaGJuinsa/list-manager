<?php


namespace Kata;


class TaskManager
{
    protected Database $db;

    public function __construct($db)
    {
        $db->init();
        $this->db = $db;
    }

    public function getTasksFromList(int $listId)
    {
        return $this->db->select('task', ['list_id' => $listId]);
    }

    public function createNewTask(string $taskName, int $listId)
    {
        if ( ! empty($this->db->select('task', ['name' => $taskName, 'list_id' => $listId]))) {
            throw new \Exception('Another task is created with the same name in the same list');
        }

        return $this->db->insert('task', ['name' => $taskName, 'list_id' => $listId]);
    }
}