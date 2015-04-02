# Laravel Micro Framework

Enjoy using Laravel? Have you ever tought when you built a simple / mini application but you used a atomic bomb to kill an ant?
Let me introduce to you, **A Micro Version of Laravel Framework**. Built using Laravel component, this Micro Framework includes:

- Laravel Routing
- Laravel Blade
- Laravel Eloquent ORM
- Laravel Authentication
- Laravel MongoDB
- Symfony Exception Handler

### Example Usage

```php
$baseDir = __DIR__;

with(new Micro($baseDir))
    ->gatherRoutes(function ($app)
    {
        $app['router']->get('/', function ()
        {
            echo 'Hello from Micro Framework';
        });

        // Or, just include your routes.php script
        //require $app->basePath('/path/to/your/routes.php');
    })
    ->handle(Request::createFromBase(Request::createFromGlobals()))
    ->send();
```

### Installing

```sh
git clone https://github.com/krisanalfa/micro-laravel
cd micro-laravel
bower install
composer install
# cd public && php -S 0.0.0.0:8000
```

### Why Micro Framework?

- It's tiny, so it has a good performance
- "Debloated", means you don't have any unused packages in your app
- You will NOT lose your enjoyness using Laravel when you try this Micro Framework

### Why MongoDB?

Because it's simple. You don't need a structural database to make a simple application using this Micro Framework.
There's no concern to think about database schema, go ahead, build a small yet wonderful app using Micro.
Feel free to fork, or you think that there are some providers in Laravel should exist in Micro Framework but they're not, just request me a "New Feature" for next release.

I'm using [Bootstrap Material Design](https://github.com/FezVrasta/bootstrap-material-design) by [FezVrasta](https://github.com/FezVrasta).
It's an amazing Bootstrap Theme! Have a look, this hero is awesome!
