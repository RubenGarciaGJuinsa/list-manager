<?php


namespace Almacen\Core\Db;


class DbPostgreSQL implements DbInterface, DbApplicationInterface
{
    private static $instance;
    private $conn;

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
        $this->conn = pg_connect("host=$this->host dbname=$this->dbName user=$this->user password=$this->password");
    }

    public static function init(array $config): DbInterface
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

        $fieldNumber = 1;
        foreach ($values as $name => $value) {
            if ( ! empty($fields)) {
                $fields .= ', ';
                $valuesString .= ', ';
            }

            $fields .= $name;
            $valuesString .= '$'.$fieldNumber;

            $fieldNumber++;
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
        $dbResult = pg_query_params($this->conn, $sql, $params);

        return pg_affected_rows($dbResult);
    }

    public function select(string $tableName, array $fields, array $conditions = []): array
    {
        $sql = $this->prepareSqlSelect($tableName, $fields, $conditions);

        return $this->executeSelect($sql, $conditions);
    }

    protected function prepareSqlSelect(string $tableName, array $fields, array $conditions = []): string
    {
        $sql = 'SELECT '.implode(', ', $fields).' FROM '.$tableName;

        if ( ! empty($conditions)) {
            $where = '';

            $fieldNumber = 1;
            foreach ($conditions as $field => $value) {
                if ( ! empty($where)) {
                    $where .= 'AND ';
                }
                $where .= $field.' = $'.$fieldNumber.' ';
                $fieldNumber++;
            }

            $sql .= ' WHERE '.$where;
        }

        return $sql;
    }

    protected function executeSelect(string $sql, array $conditions = [])
    {
        $result = [];

        $dbResult = pg_query_params($this->conn, $sql, $conditions);

        while ($line = pg_fetch_array($dbResult, null, PGSQL_ASSOC)) {
            $result[] = $line;
        }

        return $result;
    }
}