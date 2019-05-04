<?php

namespace Tests;

use Phalcon\Di;
use Phalcon\Mvc\Dispatcher;
use Phalcon\Di\FactoryDefault;
use Phalcon\Events\Manager;
use AntonAm\Phalcon\Middleware\Event;
use AntonAm\Phalcon\Middleware\MiddlewareException;
use Exception;

/**
 * Class MiddlewareCest
 *
 * @package Tests
 */
class MiddlewareCest
{
    /** @var Dispatcher */
    private $dispatcher;

    /**
     * @inheritDoc
     */
    public function _before(): void
    {
        Di::reset();

        $di = new FactoryDefault();
        $di->set(
            'dispatcher',
            function() {
                $eventsManager = new Manager();
                $eventsManager->attach(
                    'dispatch',
                    new Event()
                );

                $dispatcher = new Dispatcher();
                $dispatcher->setEventsManager($eventsManager);
                $dispatcher->setDefaultNamespace("Tests\\");
                return $dispatcher;
            },
            true
        );

        $this->dispatcher = $di->get('dispatcher');
    }


    /**
     * @param UnitTester $I
     * @throws Exception
     */
    public function middlewareReturnTrue(UnitTester $I): void
    {
        /** @var Dispatcher $dispatcher */
        $dispatcher = $this->dispatcher;

        $dispatcher->setControllerName('test');
        $dispatcher->setActionName('success');

        $dispatcher->dispatch();

        $I->assertEquals(
            TestController::STANDARD_MESSAGE,
            $dispatcher->getReturnedValue()
        );
    }


    /**
     * @param UnitTester $I
     * @throws Exception
     */
    public function middlewareReturnFalse(UnitTester $I): void
    {
        /** @var Dispatcher $dispatcher */
        $dispatcher = $this->dispatcher;

        $dispatcher->setControllerName('test');
        $dispatcher->setActionName('denied');

        $I->expectThrowable(
            MiddlewareException::class,
            function() use ($dispatcher) {
                $dispatcher->dispatch();
            }
        );
    }

    /**
     * @param UnitTester $I
     * @throws Exception
     */
    public function multipleMiddlewareReturnTrue(UnitTester $I): void
    {
        /** @var Dispatcher $dispatcher */
        $dispatcher = $this->dispatcher;

        $dispatcher->setControllerName('test');
        $dispatcher->setActionName('multipleSuccess');

        $dispatcher->dispatch();

        $I->assertEquals(
            TestController::STANDARD_MESSAGE,
            $dispatcher->getReturnedValue()
        );
    }

    /**
     * @param UnitTester $I
     * @throws Exception
     */
    public function multipleMiddlewareReturnFalse(UnitTester $I): void
    {
        /** @var Dispatcher $dispatcher */
        $dispatcher = $this->dispatcher;

        $dispatcher->setControllerName('test');
        $dispatcher->setActionName('multipleDenied');

        $I->expectThrowable(
            MiddlewareException::class,
            function() use ($dispatcher) {
                $dispatcher->dispatch();
            }
        );
    }

    /**
     * @param UnitTester $I
     * @throws Exception
     */
    public function workWithoutMiddleware(UnitTester $I): void
    {
        /** @var Dispatcher $dispatcher */
        $dispatcher = $this->dispatcher;

        $dispatcher->setControllerName('test');
        $dispatcher->setActionName('noMiddleware');

        $dispatcher->dispatch();

        $I->assertEquals(
            TestController::STANDARD_MESSAGE,
            $dispatcher->getReturnedValue()
        );
    }


    /**
     * @param UnitTester $I
     */
    public function middlewareClassIsNotExist(UnitTester $I): void
    {
        /** @var Dispatcher $dispatcher */
        $dispatcher = $this->dispatcher;

        $dispatcher->setControllerName('test');
        $dispatcher->setActionName('classIsNotExist');

        $I->expectThrowable(
            MiddlewareException::class,
            function() use ($dispatcher) {
                $dispatcher->dispatch();
            }
        );
    }

    /**
     * @param UnitTester $I
     */
    public function middlewareClassIsNotMiddleware(UnitTester $I): void
    {
        /** @var Dispatcher $dispatcher */
        $dispatcher = $this->dispatcher;

        $dispatcher->setControllerName('test');
        $dispatcher->setActionName('isNotMiddleware');

        $I->expectThrowable(
            MiddlewareException::class,
            function() use ($dispatcher) {
                $dispatcher->dispatch();
            }
        );
    }

    /**
     * @param UnitTester $I
     */
    public function changeDefaultMiddlewareName(UnitTester $I): void
    {
        Di::reset();
        $di = new FactoryDefault();
        $di->set(
            'dispatcher',
            function() {
                $eventsManager = new Manager();
                $eventsManager->attach(
                    'dispatch',
                    new Event('AuthMiddleware')
                );

                $dispatcher = new Dispatcher();
                $dispatcher->setEventsManager($eventsManager);
                $dispatcher->setDefaultNamespace("Tests\\");
                return $dispatcher;
            },
            true
        );

        $dispatcher = $di->get('dispatcher');
        $dispatcher->setControllerName('test');
        $dispatcher->setActionName('authMiddleware');
        $dispatcher->dispatch();
        $I->assertEquals(
            TestController::STANDARD_MESSAGE,
            $dispatcher->getReturnedValue()
        );
    }
}
