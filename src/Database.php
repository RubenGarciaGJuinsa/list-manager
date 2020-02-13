<?php


namespace Kata;


use Almacen\Core\Application;
use Almacen\Core\Db\DbApplicationInterface;
use Almacen\Core\Db\DbInterface;

class Database implements DbInterface, DbApplicationInterface
{
    private static $instance;

    protected static \SQLite3 $conn;
    protected $dbFile;

    public function __construct()
    {

    }

    public static function init(array $config): DbInterface
    {
        if (empty(static::$instance)) {
            $instance = new static();
            $instance->dbFile = $config['dbFile'];

            $instance->connect();
            static::$instance = $instance;
        }

        return static::$instance;
    }

    protected function connect()
    {
        if (empty(static::$conn)) {
            static::$conn = new \SQLite3($this->dbFile);
        }
    }

    public function select(string $table, array $fields, array $conditions = []): array
    {
        $sql = 'SELECT '.implode(', ', $fields).' FROM '.$table;
        if ( ! empty($conditions)) {
            $sqlWhere = '';

            foreach ($conditions as $field => $value) {
                if ( ! empty($sqlWhere)) {
                    $sqlWhere .= ' AND ';
                }
                $sqlWhere .= $field.'=:'.$field;
            }
            $sql .= ' WHERE '.$sqlWhere;
        }

        $stmt = static::$conn->prepare($sql);

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

    public function insert($table, $fields): ?int
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
        $stmt = static::$conn->prepare($sql);
        foreach ($fields as $fieldName => $fieldValue) {
            $stmt->bindValue(':'.$fieldName, $fieldValue, SQLITE3_TEXT);
        }

        $result = $stmt->execute();

        return static::$conn->lastInsertRowID();
    }

    public function update($table, $fields, $conditions = [])
    {
        $sql = "UPDATE $table SET ";

        $fieldNumber = 0;
        foreach ($fields as $fieldName => $fieldValue) {
            if ($fieldNumber > 0) {
                $sql .= ", ";
            }

            $sql .= "$fieldName = :u$fieldName";
            $fieldNumber++;
        }

        if ( ! empty($conditions)) {
            $sqlWhere = '';

            foreach ($conditions as $field => $value) {
                if ( ! empty($sqlWhere)) {
                    $sqlWhere .= ' AND ';
                }
                $sqlWhere .= $field.'=:w'.$field;
            }
            $sql .= " WHERE $sqlWhere";
        }

        $stmt = static::$conn->prepare($sql);
        foreach ($fields as $fieldName => $fieldValue) {
            $stmt->bindValue(':u'.$fieldName, $fieldValue, SQLITE3_TEXT);
        }
        foreach ($conditions as $fieldName => $fieldValue) {
            $stmt->bindValue(':w'.$fieldName, $fieldValue, SQLITE3_TEXT);
        }

        return $stmt->execute();
    }

    public function delete($table, $conditions = [])
    {
        $sql = "DELETE FROM $table";

        if (!empty($conditions)) {
            $sqlWhere = "";

            foreach ($conditions as $field => $value) {
                if ( ! empty($sqlWhere)) {
                    $sqlWhere .= ' AND ';
                }
                $sqlWhere .= $field.'=:'.$field;
            }
            $sql .= " WHERE $sqlWhere";
        }

        $stmt = static::$conn->prepare($sql);
        foreach ($conditions as $fieldName => $fieldValue) {
            $stmt->bindValue(':'.$fieldName, $fieldValue, SQLITE3_TEXT);
        }

        $stmt->execute();

        return static::$conn->changes();
    }

    public static function getInstance(): DbInterface
    {
        if (empty(static::$instance)) {
            Application::getInstance()->initDb();
        }

        return static::$instance;
    }
}