<?php

namespace Tests;

use Phalcon\Mvc\User\Plugin;
use AntonAm\Phalcon\Middleware\MiddlewareInterface;

/**
 * Class MiddlewareParam
 *
 * @package Tests
 */
class MiddlewareParam extends Plugin implements MiddlewareInterface
{
    /**
     * @param array $params
     * @return bool
     */
    public function handle(array $params = []): bool
    {
        return (bool)current($params);
    }
}
