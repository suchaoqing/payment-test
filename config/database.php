<?php

return [
    'migrations' => 'migrations',
    'default' => env('DB_CONNECTION', 'mysql'),

    'connections' => [
        'sqlite' => [
            'driver' => 'sqlite',
            'database' => env('DB_DATABASE', database_path('database.sqlite')),
            'prefix' => '',
        ],

        'mysql' => [
            'driver' => 'mysql',
            'host' => env('DB_HOST', '127.0.0.1'),
            'port' => env('DB_PORT', '3306'),
            'database' => env('DB_DATABASE', 'payment_test_test_dev'),
            'username' => env('DB_USERNAME', 'root'),
            'password' => env('DB_PASSWORD', 'root'),
            'unix_socket' => env('DB_SOCKET', ''),
            'charset' => 'utf8mb4',
            'collation' => 'utf8mb4_unicode_ci',
            'prefix' => '',
            'strict' => true,
            'engine' => null,
        ],
    ],

    'redis' => [
        'client' => 'predis',

        'default' => [
            'host' => env('CACHE_HOST', 'redis'),
            'password' => env('CACHE_PASSWORD', null),
            'port' => env('CACHE_PORT', 6379),
            'database' => env('CACHE_DATABASE', 0),
            'prefix' => env('CACHE_PREFIX', 'wallet'),
        ],
    ],
];
