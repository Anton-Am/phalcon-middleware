<?php

namespace AntonAm\Phalcon\Middleware;

/**
 * Interface MiddlewareInterface
 *
 * @package AntonAm\Phalcon\Middleware
 */
interface MiddlewareInterface
{

    /**
     * @param array $params
     * @return bool
     */
    public function handle(array $params = []): bool;
}
