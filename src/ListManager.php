<?php


namespace Kata;


class ListManager
{
    protected Database $db;

    public function __construct($db)
    {
        $this->db = $db;
    }

    public function getLists()
    {
        $this->db->init();
        return $this->db->getLists();
    }
}