<?php

namespace Almacen\core\Db;

interface DbApplicationInterface extends DbInterface
{
    public function insert(string $tableName, array $values): ?int;

    public function select(string $tableName, array $fields, array $conditions = []): array;
}