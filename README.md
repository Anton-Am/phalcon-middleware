AntonAm\Phalcon\Middleware
==========================

Middleware component for Phalcon MVC controllers


[![Latest Stable Version](https://poser.pugx.org/anton-am/phalcon-middleware/v/stable)](https://packagist.org/packages/anton-am/phalcon-middleware)
[![Total Downloads](https://poser.pugx.org/anton-am/phalcon-middleware/downloads)](https://packagist.org/packages/anton-am/phalcon-middleware)
[![Build Status](https://travis-ci.org/Anton-Am/phalcon-middleware.svg?branch=3.0.x)](https://travis-ci.org/Anton-Am/phalcon-middleware)

## Installing ##

Install using Composer:

```bash
composer require anton-am/phalcon-middleware
```
or add to your composer.json
```bash
"anton-am/phalcon-middleware": "^3.0.0"
```

You'll need to add the event to the `dispatcher` DI service:

```php
use AntonAm\Phalcon\Middleware\Event;
use Phalcon\Events\Manager;
use Phalcon\Mvc\Dispatcher;

// ...

$di->set(
    "dispatcher",
    function () use ($di) {
            $eventsManager = new Manager();
            
            //Attach a listener
            $eventsManager->attach(
                'dispatch:beforeExecuteRoute',
                new Event()
            );

            $dispatcher = new Dispatcher();
            $dispatcher->setEventsManager($eventsManager);
            return $dispatcher;
    },
    true
);
```

Now, you can create middleware classes:

```php
namespace Modules\Frontend\Middlewares;

use Phalcon\Mvc\User\Plugin;
use AntonAm\Phalcon\Middleware\MiddlewareInterface;

/**
 * Class CSRF
 *
 * @package Modules\Frontend\Middlewares
 */
class CSRF extends Plugin implements MiddlewareInterface
{
    /**
     * @param array $params
     * @return bool
     */
    public function handle(array $params = []): bool
    {
        if (!$this->security->checkToken()) {
            $this->flashSession->error('Wrong CSRF');
            $this->response->redirect($this->request->getHTTPReferer(), true)->send();
            return false;
        }

        return true;
    }
```



## Example ##

### Controller ###

```php
class IndexController extends \Phalcon\Mvc\Controller
{
    /**
     * @Middleware("Modules\Frontend\Middlewares\MustBeLoggedIn")
     * @Middleware("Modules\Frontend\Middlewares\HasProject")
     * @Middleware("Modules\Frontend\Middlewares\MustBeInProjectAs", "Creator")
     * @Middleware("Modules\Frontend\Middlewares\CSRF")
     */
    public function indexAction()
    {
        // ...
    }
}
```
