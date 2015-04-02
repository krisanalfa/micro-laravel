<?php

$basePath = dirname(__DIR__);

require $basePath.DIRECTORY_SEPARATOR.'vendor'.DIRECTORY_SEPARATOR.'autoload.php';

use Micro\Micro;
use Illuminate\Http\Request;

with(new Micro($basePath))
    ->gatherRoutes(function ($app)
    {
        $app['router']->get('/', function () use ($app)
        {
            return $app['view']
                ->make('home')
                ->render();
        });
    })
    ->handle(Request::createFromBase(Request::createFromGlobals()))
    ->send();
