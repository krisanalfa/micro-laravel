<?php

return [
    'default' => 'mongodb',

    'connections' => [
        'mysql' => [
            'driver'    => 'mysql',
            'host'      => 'localhost',
            'database'  => 'micro',
            'username'  => 'root',
            'password'  => 'password',
            'charset'   => 'utf8',
            'collation' => 'utf8_unicode_ci',
            'prefix'    => '',
            'strict'    => false,
        ],

        'mongodb' => [
            'driver'   => 'mongodb',
            'host'     => 'localhost',
            'port'     => 27017,
            'database' => 'micro'
        ],
    ],

    'migrations' => 'migrations',
];
