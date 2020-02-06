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
        return $this->db->getLists();
    }

    public function createList($name)
    {
        return $this->db->setList($name);
    }
}