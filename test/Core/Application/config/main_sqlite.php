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
        'class' => '\Kata\Database',
        'dbFile' => getenv('DB_CONNECTION'),
    ],
];