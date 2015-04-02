<?php

return [
    'driver'          => 'file',

    'lifetime'        => 120,

    'expire_on_close' => false,

    'encrypt'         => false,

    'files'           => Illuminate\Support\Facades\App::basePath('/storage/framework/sessions'),

    'connection'      => null,

    'table'           => 'sessions',

    'lottery'         => [2, 100],

    'cookie'          => 'micro_session',

    'path'            => '/',

    'domain'          => null,

    'secure'          => false,
];
