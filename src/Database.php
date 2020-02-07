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

    public function select($table, $conditions = [])
    {
        return $this->conn->query('SELECT * FROM '.$table)->fetchArray();
    }

    public function insert($table, $fields)
    {

        $fieldNames = '';
        $fieldValues = '';

        foreach ($fields as $fieldName => $fieldValue) {
            if ( ! empty($fieldNames)) {
                $fieldNames .= ', ';
                $fieldValues .= ', ';
            }
            $fieldNames .= $fieldName;
            $fieldValues .= ':'.$fieldName;
        }
        $sql = "INSERT INTO $table ($fieldNames) VALUES ($fieldValues)";
        $stmt = $this->conn->prepare($sql);
        foreach ($fields as $fieldName => $fieldValue) {
            $stmt->bindValue(':'.$fieldName, $fieldValue, SQLITE3_TEXT);
        }

        return $stmt->execute();
    }
}