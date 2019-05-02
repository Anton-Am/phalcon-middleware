<?php

namespace AntonAm\Phalcon\Middleware;

use Phalcon\Mvc\Dispatcher;
use Phalcon\Mvc\User\Plugin;
use Phalcon\Events\Event as PhalconEvent;
use Exception;

/**
 * Class Event
 *
 * @package AntonAm\Phalcon\Middleware
 */
class Event extends Plugin
{
    /**
     * @var string Middleware annotation name
     */
    private $annotationName;

    public function __construct($annotationName = 'Middleware')
    {
        $this->annotationName = $annotationName;
    }

    /**
     * @param PhalconEvent $event
     * @param Dispatcher $dispatcher
     * @param $data
     * @return bool
     * @throws Exception
     */
    public function beforeExecuteRoute(PhalconEvent $event, Dispatcher $dispatcher, $data): bool
    {
        $methodAnnotations = $this->annotations->getMethod(
            $dispatcher->getHandlerClass(),
            $dispatcher->getActiveMethod()
        );

        if (!$methodAnnotations->has($this->annotationName)) {
            return true;
        }

        foreach ($methodAnnotations->getAll($this->annotationName) as $annotation) {
            $arguments = $annotation->getArguments();
            $class = array_shift($arguments);

            $middleware = new $class();

            if (!($middleware instanceof MiddlewareInterface)) {
                throw new MiddlewareException(
                    'Middleware interface is not implemented'
                );
            }

            if ($middleware->handle($arguments) === false) {
                throw new MiddlewareException(
                    sprintf('Middleware %s return false result', $class)
                );
            }
        }

        return true;
    }
}
