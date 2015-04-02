<?php namespace Micro\Handler;

use Exception;
use ErrorException;
use Illuminate\Container\Container;
use Request;
use Config;
use Illuminate\Http\Response;
use Symfony\Component\Debug\ExceptionHandler;

class ErrorHandler
{
    protected $app;

    protected $debug;

    /**
     * Bootstrap the given application.
     *
     * @param  \Illuminate\Container\Container  $app
     *
     * @return void
     */
    public function bootstrap(Container $app)
    {
        error_reporting(-1);

        set_error_handler([$this, 'handleError']);

        set_exception_handler([$this, 'handleException']);

        register_shutdown_function([$this, 'handleShutdown']);

        if ( ! $this->debug = Config::get('app.debug'))
        {
            ini_set('display_errors', 'Off');
        }
    }

    /**
     * Convert a PHP error to an ErrorException.
     *
     * @param  int  $level
     * @param  string  $message
     * @param  string  $file
     * @param  int  $line
     * @param  array  $context
     *
     * @return void
     *
     * @throws \ErrorException
     */
    public function handleError($level, $message, $file = '', $line = 0, $context = [])
    {
        if (error_reporting() & $level)
        {
            throw new ErrorException($message, 0, $level, $file, $line);
        }
    }

    /**
     * Handle an uncaught exception from the application.
     *
     * Note: Most exceptions can be handled via the try / catch block in
     * the HTTP and Console kernels. But, fatal error exceptions must
     * be handled differently since they are not normal exceptions.
     *
     * @param  \Exception  $e
     *
     * @return void
     */
    public function handleException($e)
    {
        $this->renderHttpResponse($e);
    }

    /**
     * Render an exception as an HTTP response and send it.
     *
     * @param  \Exception  $e
     *
     * @return void
     */
    protected function renderHttpResponse($e)
    {
        $this->render($this->app['request'], $e)->send();
    }

    /**
     * Handle the PHP shutdown event.
     *
     * @return void
     */
    public function handleShutdown()
    {
        if ( ! is_null($error = error_get_last()) && $this->isFatal($error['type']))
        {
            $this->handleException($this->fatalExceptionFromError($error));
        }
    }

    /**
     * Create a new fatal exception instance from an error array.
     *
     * @param  array  $error
     *
     * @return \Symfony\Component\Debug\Exception\FatalErrorException
     */
    protected function fatalExceptionFromError(array $error)
    {
        return new FatalErrorException(
            $error['message'], $error['type'], 0, $error['file'], $error['line']
        );
    }

    /**
     * Determine if the error type is fatal.
     *
     * @param  int  $type
     *
     * @return bool
     */
    protected function isFatal($type)
    {
        return in_array($type, [E_ERROR, E_CORE_ERROR, E_COMPILE_ERROR, E_PARSE]);
    }

    /**
     * Render an exception into a response.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Exception  $e
     *
     * @return \Illuminate\Http\Response
     */
    public function render($request, Exception $e)
    {
        return with(new ExceptionHandler($this->debug))->createResponse($e);
    }
}
