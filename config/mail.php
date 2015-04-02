<?php

return [
    'driver'     => 'smtp',

    'host'       => 'smtp.mailgun.org',

    'port'       => 587,

    'from'       => [
        'address' => null,
        'name'    => null
    ],

    'encryption' => 'tls',

    'username'   => '',

    'password'   => '',

    'sendmail'   => '/usr/sbin/sendmail -bs',

    'pretend'    => false,

];
