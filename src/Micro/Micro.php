<?php namespace Micro;

use Closure;
use Exception;
use Illuminate\Config\Repository;
use Illuminate\Container\Container;
use Illuminate\Contracts\Config\Repository as RepositoryContract;
use Illuminate\Http\Request;
use Illuminate\Pipeline\Pipeline;
use Illuminate\Support\ClassLoader;
use Illuminate\Support\Facades\Facade;
use Micro\Handler\ErrorHandler;
use Symfony\Component\Finder\Finder;
use Symfony\Component\Finder\SplFileInfo;

class Micro extends Container
{
    protected $basePath = null;

    /**
     * @param string $basePath
     */
    public function __construct($basePath)
    {
        $this->basePath = $basePath;

        ClassLoader::register();
        ClassLoader::addDirectories([
            $this->basePath('/app/Controllers'),
            $this->basePath('/app/Models'),
        ]);

        Facade::setFacadeApplication($this);

        $this->instance('app', $this);

        $this->singleton('config', 'Illuminate\Config\Repository');

        $this->loadConfigurationFiles($this['config']);

        $this->loadBasicAliases($this);

        $this->registerProviders($this);

        $this->loadFacades($this['config']);

        $this->bootProviders($this);
    }

    /**
     * Gathering configuration file
     *
     * @param  \Illuminate\Contracts\Config\Repository $config
     *
     * @return void
     */
    protected function loadConfigurationFiles(RepositoryContract $config)
    {
        foreach ($this->getConfigurationFiles() as $key => $path)
        {
            $config->set($key, require($path));
        }
    }

    /**
     * Register basic alias for framework
     *
     * @param  \Illuminate\Container\Container $container
     *
     * @return void
     */
    protected function loadBasicAliases(Container $container)
    {
        $aliases = [
            'app'                  => ['Illuminate\Contracts\Container\Container', 'Micro'],
            'auth'                 => 'Illuminate\Auth\AuthManager',
            'auth.driver'          => ['Illuminate\Auth\Guard', 'Illuminate\Contracts\Auth\Guard'],
            'auth.password'        => ['Illuminate\Auth\Passwords\PasswordBroker', 'Illuminate\Contracts\Auth\PasswordBroker'],
            'auth.password.tokens' => 'Illuminate\Auth\Passwords\TokenRepositoryInterface',
            'blade.compiler'       => 'Illuminate\View\Compilers\BladeCompiler',
            'config'               => ['Illuminate\Config\Repository', 'Illuminate\Contracts\Config\Repository'],
            'cookie'               => ['Illuminate\Cookie\CookieJar', 'Illuminate\Contracts\Cookie\Factory', 'Illuminate\Contracts\Cookie\QueueingFactory'],
            'db'                   => 'Illuminate\Database\DatabaseManager',
            'encrypter'            => ['Illuminate\Encryption\Encrypter', 'Illuminate\Contracts\Encryption\Encrypter'],
            'events'               => ['Illuminate\Events\Dispatcher', 'Illuminate\Contracts\Events\Dispatcher'],
            'files'                => 'Illuminate\Filesystem\Filesystem',
            'filesystem'           => 'Illuminate\Contracts\Filesystem\Factory',
            'hash'                 => 'Illuminate\Contracts\Hashing\Hasher',
            'mailer'               => ['Illuminate\Mail\Mailer', 'Illuminate\Contracts\Mail\Mailer', 'Illuminate\Contracts\Mail\MailQueue'],
            'redirect'             => 'Illuminate\Routing\Redirector',
            'request'              => 'Illuminate\Http\Request',
            'router'               => ['Illuminate\Routing\Router', 'Illuminate\Contracts\Routing\Registrar'],
            'session'              => 'Illuminate\Session\SessionManager',
            'session.store'        => ['Illuminate\Session\Store', 'Symfony\Component\HttpFoundation\Session\SessionInterface'],
            'url'                  => ['Illuminate\Routing\UrlGenerator', 'Illuminate\Contracts\Routing\UrlGenerator'],
            'view'                 => ['Illuminate\View\Factory', 'Illuminate\Contracts\View\Factory'],
        ];

        foreach ($aliases as $key => $aliases)
        {
            foreach ((array) $aliases as $alias)
            {
                $container->alias($key, $alias);
            }
        }
    }

    /**
     * Get list configuration files
     *
     * @return array
     */
    protected function getConfigurationFiles()
    {
        $files = [];

        foreach (Finder::create()->files()->name('*.php')->in($this->basePath('/config')) as $file)
        {
            $nesting = $this->getConfigurationNesting($file);

            $files[$nesting.basename($file->getRealPath(), '.php')] = $file->getRealPath();
        }

        return $files;
    }

    /**
     * Get the name of file configuration
     *
     * @param  \Symfony\Component\Finder\SplFileInfo $file
     *
     * @return string
     */
    protected function getConfigurationNesting(SplFileInfo $file)
    {
        $directory = dirname($file->getRealPath());

        if ($tree = trim(str_replace($this->basePath('/config'), '', $directory), DIRECTORY_SEPARATOR))
        {
            $tree = str_replace(DIRECTORY_SEPARATOR, '.', $tree).'.';
        }

        return $tree;
    }

    /**
     * Register the providers
     *
     * @param  \Illuminate\Container\Container $app
     *
     * @return void
     */
    protected function registerProviders(Container $app)
    {
        foreach ($app['config']['app.providers'] as $boostrap)
        {
            with(new $boostrap($app))->register();
        }
    }

    /**
     * Boot the providers
     *
     * @param  \Illuminate\Container\Container $app
     *
     * @return void
     */
    protected function bootProviders(Container $app)
    {
        foreach ($app['config']['app.providers'] as $boostrap)
        {
            with(new $boostrap($app))->boot();
        }
    }

    /**
     * Load facade classes
     *
     * @param  \Illuminate\Contracts\Config\Repository $config
     *
     * @return void
     */
    protected function loadFacades(RepositoryContract $config)
    {
        foreach ($config['app.aliases'] ?: [] as $short => $long)
        {
            class_alias($long, $short);
        }
    }

    public function gatherRoutes(Closure $callable)
    {
        $callable($this);

        return $this;
    }

    /**
     * Handle request
     *
     * @param  \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\Response
     */
    public function handle(Request $request)
    {
        $this->instance('request', $request);

        $this->registerMiddlewares($this);

        with(new ErrorHandler())->bootstrap($this);

        return with(new Pipeline($this))
            ->send($request)
            ->then(function ($request)
            {
                return $this['router']->dispatch($request);
            });
    }

    /**
     * Register route middleware
     *
     * @param  \Illuminate\Container\Container $container
     *
     * @return void
     */
    protected function registerMiddlewares(Container $container)
    {
        $middlewares = $this['config']['app.middlewares'] ?: [];

        foreach ($middlewares as $abstract => $middleware)
        {
            $container->bind($abstract, $middleware);
        }
    }

    /**
     * Get base path and append it from the first argument is exist
     *
     * @param  string $append
     *
     * @return string
     */
    public function basePath($append = null)
    {
        return realpath($this->basePath.$append);
    }
}
