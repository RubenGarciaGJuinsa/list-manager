<?php


namespace Kata;


class TaskManager
{
    protected Database $db;

    public function __construct($db)
    {
//        $db->init();
        $this->db = $db;
    }

    public function getAllTasks()
    {
        return $this->db->select('task', ['id', 'list_id', 'name']);
    }

    public function getTasksFromList(int $listId)
    {
        return $this->db->select('task', ['id', 'list_id', 'name'], ['list_id' => $listId]);
    }

    public function createNewTask(string $taskName, int $listId)
    {
        if (!empty($this->validateTask($taskName, $listId))) {
            return false;
        }

        if ( ! empty($this->db->select('task', ['id'], ['name' => $taskName, 'list_id' => $listId]))) {
            throw new \Exception('Another task is created with the same name in the same list');
        }

        return $this->db->insert('task', ['name' => $taskName, 'list_id' => $listId]);
    }

    public function editTask(int $taskId, string $taskName, int $taskList)
    {
        if (!empty($this->validateTask($taskName, $taskList))) {
            return false;
        }

        if ( ! empty($this->db->select('task', ['id'], ['name' => $taskName, 'list_id' => $taskList]))) {
            throw new \Exception('Another task is created with the same name in the same list');
        }

        return $this->db->update('task', ['name' => $taskName, 'list_id' => $taskList], ['id' => $taskId]);
    }

    public function deleteTask(int $taskId)
    {
        return $this->db->delete('task', ['id' => $taskId]);
    }

    public function getTask(string $id)
    {
        $tasks = $this->db->select('task', ['id', 'list_id', 'name'], ['id' => $id]);
        return array_shift($tasks);
    }

    public function validateTask(string $taskName, int $taskList)
    {
        $result = [];
        if (empty($taskName)) {
            $result['name'][] = 'Name cannot be empty';
        }

        if (strlen($taskName) > 255) {
            $result['name'][] = 'Name max length is 255';
        }

        if (empty($taskList)) {
            $result['list_id'][] = 'List id cannot be empty';
        }

        return $result;
    }
}