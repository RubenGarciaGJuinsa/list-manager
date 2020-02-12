<?php

namespace Test\Core\Db;

use Almacen\Core\Application;
use Almacen\Core\Db\DbMysql;
use PHPUnit\Framework\TestCase;

class DbMysqlTest extends TestCase
{
    /** @var DbMysqlWrapper */
    protected $db;

    /**
     * @test
     *
     * @dataProvider  get_bind_typesProvider
     * @param $input
     * @param $expected
     */
    public function
    get_bind_types(
        $input,
        $expected
    ) {
        $result = $this->db->exposedGetBindType($input);

        $this->assertEquals($expected, $result);
    }

    public function get_bind_typesProvider()
    {
        return
            [
                [1, 'i'],
                ['2', 's'],
                ['aaaaa', 's'],
                [1.23, 'd'],
            ];
    }

    /** @test */
    public function
    given_tablename_and_parameters_get_sql_insert()
    {
        $tableName = 'tableName';
        $values = [
            'param1' => 'value1',
            'param2' => 'value2',
            'param3' => 'value3',
        ];

        $expectedSql = 'INSERT INTO tableName (param1, param2, param3) VALUES (?, ?, ?)';

        $sql = $this->db->exposedPrepareSqlInsert($tableName, $values);

        $this->assertEquals($expectedSql, $sql);
    }

    /** @test */
    public function
    prepare_params_to_statement()
    {
        $params = [1, '2', 4.54];
        $expectedPreparedParams = ['isd', 1, '2', 4.54];

        $preparedParams = $this->db->exposedPrepareParamsToStatement($params);

        $this->assertEquals($expectedPreparedParams, $preparedParams);
    }

    protected function setUp(): void
    {
        $configFiles = [
            __DIR__.'/../Application/config/main_mysql.php',
        ];
        Application::delete();
        $app = Application::getInstance($configFiles);
        $this->db = DbMysqlWrapper::getInstance();
    }
}

class DbMysqlWrapper extends DbMysql
{
    public function exposedGetBindType($value)
    {
        return $this->getBindType($value);
    }

    public function exposedPrepareSqlInsert(string $tableName, array $values): string
    {
        return $this->prepareSqlInsert($tableName, $values);
    }

    public function exposedPrepareParamsToStatement($params): array
    {
        return $this->prepareParamsToInsertStatement($params);
    }

    protected function connect()
    {
    }
}
