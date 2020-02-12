<?php

use Kata\Database;
use Kata\ListManager;

require('vendor/autoload.php');

$dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
$dotenv->load();

$db = new Database();

$taskManager = new \Kata\TaskManager($db);
//$taskManager->createNewTask('Task', 1);
$taskManager->editTask(1, 'rename', 2);
echo '<h1>It Works!</h1>';
