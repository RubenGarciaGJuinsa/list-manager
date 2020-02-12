<?php

require __DIR__.'/../vendor/autoload.php';

$dotenv = Dotenv\Dotenv::createImmutable(realpath(__DIR__.'/../'));
$dotenv->load();

$configFiles = [
    __DIR__.'/../config/main.php',
];

$app = Almacen\Core\Application::getInstance($configFiles);
$app->run();