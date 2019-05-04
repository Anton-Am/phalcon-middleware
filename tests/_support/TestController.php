<?php

namespace Tests;

use Phalcon\Mvc\Controller;

/**
 * Class TestController
 *
 * @package Tests
 */
class TestController extends Controller
{
    public const STANDARD_MESSAGE = 'Test';

    /**
     * @Middleware("Tests\MiddlewareNormal")
     */
    public function successAction(): string
    {
        return self::STANDARD_MESSAGE;
    }

    /**
     * @Middleware("Tests\MiddlewareParam", false)
     */
    public function deniedAction(): string
    {
        return "This won't work as an Exception should get thrown";
    }

    /**
     * @Middleware("Tests\MiddlewareNormal")
     * @Middleware("Tests\MiddlewareParam", true)
     */
    public function multipleSuccessAction(): string
    {
        return self::STANDARD_MESSAGE;
    }


    /**
     * @Middleware("Tests\MiddlewareNormal")
     * @Middleware("Tests\MiddlewareParam", false)
     */
    public function multipleDeniedAction(): string
    {
        return "This won't work as an Exception should get thrown";
    }

    /**
     * @return string
     */
    public function noMiddlewareAction(): string
    {
        return self::STANDARD_MESSAGE;
    }

    /**
     * @Middleware("Tests\NoMiddleware")
     */
    public function classIsNotExistAction(): string
    {
        return "This won't work as an Exception should get thrown";
    }

    /**
     * @Middleware("Tests\TestController")
     */
    public function isNotMiddlewareAction(): string
    {
        return "This won't work as an Exception should get thrown";
    }

    /**
     * @return string
     * @AuthMiddleware("Tests\MiddlewareNormal")
     */
    public function authMiddlewareAction(): string
    {
        return self::STANDARD_MESSAGE;
    }

}
