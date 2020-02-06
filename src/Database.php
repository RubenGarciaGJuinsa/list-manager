<?php


namespace Kata;


class Database
{
    protected \SQLite3 $conn;

    public function init()
    {
        $this->connect();
    }

    protected function connect()
    {
        $this->conn = new \SQLite3('database.sqlite');
    }

    public function select($table)
    {
        return $this->conn->query('SELECT * FROM '.$table);
    }

    public function setList($name)
    {
        $stmt = $this->conn->prepare('INSERT INTO list (name) VALUES (:name);');
        $stmt->bindValue(':name', $name, SQLITE3_TEXT);

        return $stmt->execute();
    }
}