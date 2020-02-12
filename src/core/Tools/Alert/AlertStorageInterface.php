<?php


namespace Almacen\core\Tools\Alert;


interface AlertStorageInterface
{
    public function pushMessage(string $level, string $message);

    public function getMessages($level = ''): array;
}