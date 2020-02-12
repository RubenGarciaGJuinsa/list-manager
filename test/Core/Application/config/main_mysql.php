<?php

return [
    'name' => 'App',
    'routers' => [
        [
            'class' => '\Test\Core\FakeRouter',
            'defaultNamespace' => 'Test\Core',
            'defaultController' => 'EmptyController',
            'defaultAction' => 'actionTest',
        ],
    ],
    'db' => [
        'class' => '\Test\Core\Db\DbMysqlWrapper',
        'user' => 'fake_user',
        'password' => 'fake_password',
        'host' => 'fake_host',
        'dbName' => 'fake_db_name',
    ],
];