<?php

return [
    'driver'   => 'eloquent',

    'model'    => 'User',

    'table'    => 'users',

    'password' => [
        'email'  => 'emails.password',
        'table'  => 'password_resets',
        'expire' => 60,
    ],
];
