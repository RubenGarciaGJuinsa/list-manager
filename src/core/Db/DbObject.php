<?php


namespace Almacen\Core\Db;


use Almacen\Core\BaseObject;


/**
 * Class DbObject
 * @var int|string $primaryKey
 */
abstract class DbObject extends BaseObject implements DbObjectInterface
{
    protected DbApplicationInterface $database;

    public function __construct($data = [])
    {
        parent::__construct($data);

        $this->database = Db::getInstance();
    }

    public function __get($name)
    {
        if ($name == 'primaryKey' && property_exists($this, 'id')) {
            $name = 'id';
        }

        return $this->$name;
    }

    public function save($validate = true): bool
    {
        if ( ! isset($this->primaryKey)) {
            return $this->create($validate);
        }

        return $this->update($validate);
    }

    public function create($validate = true): bool
    {
        return (($validate && $this->validate()) || ! $validate) && $this->executeCreate();
    }

    public function update($validate = true): bool
    {
        return (($validate && $this->validate()) || ! $validate) && $this->executeUpdate();
    }
}