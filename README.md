# Welcome to Jolt - Energize Your Application

Jolt is a new PHP5.3+ framework aimed at fast and efficient web app development. Originally named Artisan System, Jolt was renamed to be more concise and easier to speak. Many of the ideas of Artisan System will be used in Jolt, but a lot of new ideas will be implemented as well.

Jolt is built for handling routes, views, and controllers efficiently. In other words, Routes, Routing, Views, Controllers, and Layouts is what Jolt does very well. Models are decoupled from Jolt for several reasons, the biggest being that if you build an application on Jolt and decide to change later, you can move the display logic much easier than the business logic (which should be in your Models anyway: remember, fat models, skinny controllers). Jolt is built for speed, both in development time and server response time. A good framework provides a foundation for building your application and doesn't get in the way.

I suggest you use my other Model based framework, [DataModeler](http://github.com/leftnode/DataModeler) for building easily testable fat Models. 

## Why *another* PHP framework?
There are plenty of competent frameworks already in existence out there. So why am I building another one?

* I think some of the other frameworks are too large and get in the way of doing things.
* I enjoy programming and doing web development.
* I actually like PHP development and think you can build powerful stuff with it.
* There are few frameworks in the PHP5.3 world of things. Jolt will be PHP5.3+ only.
* I have a bit of NIH syndrome and like to write my own frameworks for building applications.
* Learning TDD really well.

Jolt is hosted at [Joltcore.org](http://joltcore.org). Jolt bugs can be found at [Bugs.Joltcore.org](http://bugs.joltcore.org).

## Team Members
* Vic Cherubini <vmc@leftnode.com>

## Sponsored By
* Leftnode Software, Inc. <http://leftnodesoftware.com>

## A Sample Jolt Application
    <?php

    declare(encoding='UTF-8');
    
    use \Jolt\Controller\Locator as ControllerLocator,
      \Jolt\Client,
      \Jolt\Configuration,
      \Jolt\Dispatcher,
      \Jolt\Jolt,
      \Jole\Route,
      \Jolt\Route\Named\NamedGet,
      \Jolt\Route\Named\NamedPost;

    // Configuration
    $configuration = new Configuration;
    $configuration->layoutPath = '/path/to/layout/directory/';
    $configuration->controllerPath = '/path/to/controller/directory/';
    $configuration->viewPath = '/path/to/view/directory/';
    $configuration->url = 'http://jolt.dev';
    $configuration->secureUrl = 'https://jolt.dev';
    $configuration->useRewrite = true;

    // Configure the routes and router, of course, this could be pushed to another file
    $router = new Router;
    $router->setParameters($_GET)
      ->setRequestMethod($_SERVER['REQUEST_METHOD']) // You can have Jolt only handle certain types of requests
      ->setHttp404Route(new Named('GET', '/', 'NotFound', 'index'));
    
    // You're not required to put your route names in namespaces, but it's encouraged
    $router->addRoute(new NamedGet('/', 'JoltApp\\Controller', 'action'))
      ->addRoute(new NamedGet('/abc', 'JoltApp\\Controller', 'actionAbc'))
      ->addRoute(new NamedPost('/product/%n', 'JoltApp\\Product', 'view'))
      ->addRoute(new NamedPost('/customer', 'JoltApp\\Customer', 'index'));

    // Controller\Locator object for finding and building new controllers
    $controllerLocator = new ControllerLocator;

    // Dispatcher loads up a matched route and executes it
    $dispatcher = new Dispatcher;

    // Client returns data back to the browser, has a nice __toString()
    $client = new Client;

    // Build a view to attach to the Dispatcher -> Controller heirarchy
    $view = new View;

    // Build the entire application and execute it
    $jolt = new Jolt;
    $jolt->setConfiguration($configuration);
    $jolt->attachRouter($router);
    $jolt->attachDispatcher($dispatcher);
    $jolt->attachClient($client);
    $jolt->attachLocator($controllerLocator);
    $jolt->attachView($view);

    echo $jolt->execute();

    /*
    public function Jolt::execute() {
      $c = $this->configuration;
      
      // Set up the View
      $this->view->setViewPath($this->configuration->viewPath)
        ->setUrl($c->url)
        ->setSecureUrl($c->secureUrl)
        ->setUseRewrite($c->useRewrite);

      // Get the matched route based on the URL parameters
      $matchedRoute = $this->router->execute();

      // Load and execute the matched Controller and Action from the matched route
      $this->dispatcher
        ->setControllerPath($c->controllerPath)
        ->attachLocator($this->locator)
        ->attachRoute($matchedRoute)
        ->attachView($this->view)
        ->execute();

      // Determine what the headers and HTTP status are and return that
      $this->client
        ->attachDispatcher($this->dispatcher)
        ->execute();

      return $this->client;
    }
    */
