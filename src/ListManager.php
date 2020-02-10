<?php


namespace Kata;


class ListManager
{
    protected Database $db;

    public function __construct($db)
    {
        $db->init();
        $this->db = $db;
    }

    public function getLists()
    {
        return $this->db->select('list');
    }

    public function createList($name)
    {
        if (!empty($this->db->select('list', ['name' => $name]))) {
            throw new \Exception('Another list is created with the same name');
        }
        return $this->db->insert('list', ['name' => $name]);
    }

    public function getTasksFromList(int $listId)
    {
        return $this->db->select('task', ['list_id' => $listId]);
    }
}