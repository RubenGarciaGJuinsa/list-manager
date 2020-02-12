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
        if (empty($this->conn)) {
            $this->conn = new \SQLite3(getenv('DB_CONNECTION'));
        }
    }

    public function select($table, $conditions = [])
    {
        $sql = 'SELECT * FROM '.$table;
        if ( ! empty($conditions)) {
            $sqlWhere = '';

            foreach ($conditions as $field => $value) {
                if (!empty($sqlWhere)) {
                    $sqlWhere .= ' AND ';
                }
                $sqlWhere .= $field.'=:'.$field;
            }
            $sql .= ' WHERE '.$sqlWhere;
        }

        $stmt = $this->conn->prepare($sql);

        if ( ! empty($conditions)) {
            foreach ($conditions as $field => $value) {
                $stmt->bindValue(':'.$field, $value, SQLITE3_TEXT);
            }
        }

        $result = $stmt->execute();
        $resultArray = [];
        while ($row = $result->fetchArray(SQLITE3_ASSOC)) {
            $resultArray[] = $row;
        }

        return $resultArray;
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