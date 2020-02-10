<?php

use Kata\Database;
use Kata\ListManager;

require('vendor/autoload.php');

$dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
$dotenv->load();

$listManager = new ListManager(new Database());
$lists = $listManager->getLists();

echo '<h1>It Works!</h1>';
echo '<pre>'.print_r($lists, 1).'</pre>';