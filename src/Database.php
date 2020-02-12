<?php


namespace Kata;


use Almacen\core\Db\DbApplicationInterface;
use Almacen\Core\Db\DbInterface;

class Database implements DbInterface, DbApplicationInterface
{
    private static $instance;

    protected \SQLite3 $conn;
    protected $dbName;

    protected function __construct(array $config)
    {
        $this->dbName = $config['dbName'];

        $this->connect();
    }

    public static function init(array $config): DbInterface
    {
        static::$instance = new static($config);

        return static::$instance;
    }

    protected function connect()
    {
        if (empty($this->conn)) {
            $this->conn = new \SQLite3($this->dbName);
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
        $stmt = $this->conn->prepare($sql);
        foreach ($fields as $fieldName => $fieldValue) {
            $stmt->bindValue(':'.$fieldName, $fieldValue, SQLITE3_TEXT);
        }

        return $stmt->execute()->numColumns();
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

        $stmt = $this->conn->prepare($sql);
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

    }
}