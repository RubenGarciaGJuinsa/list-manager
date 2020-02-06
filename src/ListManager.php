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
        return $this->db->insert('list', ['name' => $name]);
    }
}