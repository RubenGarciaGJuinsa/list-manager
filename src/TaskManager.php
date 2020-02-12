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
}