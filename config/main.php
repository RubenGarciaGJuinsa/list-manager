<?php
return [
    'name' => 'List Manager',
    'routers' => [
        [
            'class' => '\Almacen\Core\UrlParser\DefaultRouter',
            'defaultNamespace' => 'Kata\Controllers',
            'defaultController' => 'SiteController',
            'defaultAction' => 'actionIndex',
        ],
    ],
    'db' => [
        'class' => '\Kata\Database',
        'dbFile' => getenv('DB_CONNECTION'),
    ],
];