<?php


namespace Almacen\Core\Db;


use Almacen\Core\Application;
use mysqli;
use mysqli_stmt;

class DbMysql implements DbInterface, DbApplicationInterface
{
    private static $instance;
    private mysqli $conn;

    private string $host;
    private string $user;
    private string $password;
    private string $dbName;

    private function __construct(array $config)
    {
        $this->host = $config['host'];
        $this->user = $config['user'];
        $this->password = $config['password'];
        $this->dbName = $config['dbName'];

        $this->connect();
    }

    protected function connect()
    {
        $this->conn = new mysqli(
            $this->host,
            $this->user,
            $this->password,
            $this->dbName
        );
    }

    public static function getInstance(): DbInterface
    {
        if (empty(static::$instance)) {
            Application::getInstance()->initDb();
        }

        return static::$instance;
    }

    public static function init(array $config, $force = false): DbInterface
    {
        static::$instance = new static($config);

        return static::$instance;
    }

    public function insert(string $tableName, array $values): ?int
    {
        $sql = $this->prepareSqlInsert($tableName, $values);

        return $this->executeInsert($sql, $values);
    }

    /**
     * @param string $tableName
     * @param array $values
     * @return array
     */
    protected function prepareSqlInsert(string $tableName, array $values): string
    {
        $sql = 'INSERT INTO '.$tableName.' ({fields}) VALUES ({values})';
        $fields = '';
        $valuesString = '';

        foreach ($values as $name => $value) {
            if ( ! empty($fields)) {
                $fields .= ', ';
                $valuesString .= ', ';
            }

            $fields .= $name;
            $valuesString .= '?';
        }
        $sql = str_replace(['{fields}', '{values}'], [$fields, $valuesString], $sql);

        return $sql;
    }

    /**
     * @param $sql
     * @param $params
     *
     * @return int
     */
    protected function executeInsert($sql, $params): int
    {
        $stmt = $this->conn->prepare($sql);
        $this->bindParamsToStatement($stmt, $params);

        $stmt->execute();
        $insertId = $stmt->insert_id;
        $stmt->close();

        return $insertId;
    }

    /**
     * @param $params
     * @param mysqli_stmt $stmt
     */
    protected function bindParamsToStatement(mysqli_stmt &$stmt, $params)
    {
        $bindValues = $this->prepareParamsToInsertStatement($params);

        call_user_func_array([$stmt, 'bind_param'], $bindValues);
    }

    /**
     * @param $params
     * @return array
     */
    protected function prepareParamsToInsertStatement($params): array
    {
        $bindValues = [];
        $bindTypes = '';
        foreach ($params as $index => $param) {
            $bindTypes .= $this->getBindType($param);
            $bind_name = 'bind'.$index;
            $$bind_name = $params[$index];
            $bindValues[] = &$$bind_name;
        }
        array_unshift($bindValues, $bindTypes);

        return $bindValues;
    }

    protected function getBindType($value)
    {
        if (is_double($value)) {
            return 'd';
        }

        if (is_int($value)) {
            return 'i';
        }

        return 's';
    }

    public function select(string $tableName, array $fields, array $conditions = []): array
    {
        $sql = $this->prepareSqlSelect($tableName, $fields, $conditions);

        return $this->executeSelect($sql, $conditions);
    }

    /**
     * @param string $tableName
     * @param array $fields
     * @return string
     */
    protected function prepareSqlSelect(string $tableName, array $fields, array $conditions = []): string
    {
        $sql = 'SELECT '.implode(', ', $fields).' FROM '.$tableName;

        if ( ! empty($conditions)) {
            $where = '';

            foreach ($conditions as $field => $value) {
                if ( ! empty($where)) {
                    $where .= 'AND ';
                }
                $where .= $field.' = ? ';
            }

            $sql .= ' WHERE '.$where;
        }

        return $sql;
    }

    protected function executeSelect(string $sql, array $conditions = [])
    {
        $result = [];
        $stmt = $this->conn->prepare($sql);

        if ( ! empty($conditions)) {
            $this->bindParamsToStatement($stmt, $conditions);
        }

        $stmt->execute();

        $fieldsMetadata = $stmt->result_metadata();
        if ( ! empty($fieldsMetadata)) {
            $params = [];
            $rowFields = [];
            while ($field = $fieldsMetadata->fetch_field()) {
                $params[] = &$rowFields[$field->name];
            }

            call_user_func_array([$stmt, 'bind_result'], $params);

            while ($stmt->fetch()) {
                $row = [];
                foreach ($rowFields as $key => $val) {
                    $row[$key] = $val;
                }
                $result[] = $row;
            }
        }

        return $result;
    }

    public function executeFile(string $filePath): bool
    {
        // TODO: Implement executeFile() method.
        return false;
    }
}