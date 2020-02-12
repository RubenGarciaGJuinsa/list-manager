<?php


namespace Almacen\core\Db;


interface DbObjectInterface
{
    public function getTableName(): string;

    public function save($validate = true): bool;

    public function create($validate = true): bool;

    public function executeCreate(): bool;

    public function update($validate = true): bool;

    public function executeUpdate(): bool;

    public function delete(): bool;
}