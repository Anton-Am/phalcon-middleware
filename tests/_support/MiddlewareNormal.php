<?php

namespace Tests;

use Phalcon\Mvc\User\Plugin;
use AntonAm\Phalcon\Middleware\MiddlewareInterface;

/**
 * Class MiddlewareNormal
 *
 * @package Tests
 */
class MiddlewareNormal extends Plugin implements MiddlewareInterface
{
    /**
     * @param array $params
     * @return bool
     */
    public function handle(array $params = []): bool
    {
        return true;
    }
}
