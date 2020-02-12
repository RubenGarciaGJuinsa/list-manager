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
        'class' => 'Test\Core\Fake\FakeDb',
    ],
];