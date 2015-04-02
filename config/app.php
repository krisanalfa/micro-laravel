<?php

return [
    'name' => 'Micro',

    'debug' => true,

    'providers' => [
        'Illuminate\Auth\AuthServiceProvider',
        'Illuminate\Cookie\CookieServiceProvider',
        'Illuminate\Database\DatabaseServiceProvider',
        'Illuminate\Events\EventServiceProvider',
        'Illuminate\Filesystem\FilesystemServiceProvider',
        'Illuminate\Hashing\HashServiceProvider',
        'Illuminate\Routing\RoutingServiceProvider',
        'Illuminate\Routing\ControllerServiceProvider',
        'Illuminate\Session\SessionServiceProvider',
        'Illuminate\View\ViewServiceProvider',
        'Illuminate\Mail\MailServiceProvider',

        'Jenssegers\Mongodb\MongodbServiceProvider',
    ],

    'aliases' => [
        'App'      => 'Illuminate\Support\Facades\App',
        'Auth'     => 'Illuminate\Support\Facades\Auth',
        'Blade'    => 'Illuminate\Support\Facades\Blade',
        'Config'   => 'Illuminate\Support\Facades\Config',
        'Cookie'   => 'Illuminate\Support\Facades\Cookie',
        'DB'       => 'Illuminate\Support\Facades\DB',
        // 'Eloquent' => 'Illuminate\Database\Eloquent\Model',
        'Eloquent' => 'Jenssegers\Mongodb\Model',
        'Event'    => 'Illuminate\Support\Facades\Event',
        'Facade'   => 'Illuminate\Support\Facades\Facade',
        'File'     => 'Illuminate\Support\Facades\File',
        'Hash'     => 'Illuminate\Support\Facades\Hash',
        'Input'    => 'Illuminate\Support\Facades\Input',
        'Password' => 'Illuminate\Support\Facades\Password',
        'Redirect' => 'Illuminate\Support\Facades\Redirect',
        'Request'  => 'Illuminate\Support\Facades\Request',
        'Response' => 'Illuminate\Support\Facades\Response',
        'Route'    => 'Illuminate\Support\Facades\Route',
        'Schema'   => 'Illuminate\Support\Facades\Schema',
        'Session'  => 'Illuminate\Support\Facades\Session',
        'Storage'  => 'Illuminate\Support\Facades\Storage',
        'URL'      => 'Illuminate\Support\Facades\URL',
        'View'     => 'Illuminate\Support\Facades\View',
    ],

    'middlewares' => [
        'auth.basic' => 'Illuminate\Auth\Middleware\AuthenticateWithBasicAuth',
    ]
];
