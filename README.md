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

Jolt is hosted at [Joltcore.org](http://joltcore.org).

## Team Members
* Vic Cherubini <vmc@codegroove.net>

## Sponsored By
* Leftnode Software, Inc. <http://leftnodesoftware.com>

## A Sample Jolt Application
    <?php

    // Configuration
    $configuration = new \stdClass;
    $configuration->layoutDirectory = '/path/to/layout/directory/';
    $configuration->controllerDirectory = '/path/to/controller/directory/';
    $configuration->viewDirectory = '/path/to/view/directory/';

    // Routes and the router
    $router = new \Jolt\Router;
    $router->setInputVariables($_REQUEST);
    $router->addRoute(new \Jolt\Route\Named('/', 'Controller', 'action'))
			->addRoute(new \Jolt\Route\Named('/abc', 'Controller', 'actionAbc'))
    	->addRoute(new \Jolt\Route\Named('/product/%n', 'Product', 'view'))
    	->addRoute(new \Jolt\Route\Named('/customer', 'Customer', 'index'));

    // Dispatcher loads up a matched route and executes it
    $dispatcher = new \Jolt\Dispatcher;
    $dispatcher->setControllerDirectory($configuration->controllerDirectory);
    $dispatcher->setRoute($matchedRoute);

    // Client returns data back to the browser, has a nice __toString()
    $client = new \Jolt\Client;


    $jolt = new \Jolt;
    $jolt->setConfiguration($configuration);
    $jolt->attachRouter($router);
    $jolt->attachDispatcher($dispatcher);
    $jolt->attachClient($client);

    echo $jolt->execute();

    /*
    public function execute() {

    	// Get the matched route based on the URL parameters
    	$matchedRoute = $this->router->execute();

    	// Load and execute the matched Controller and Action from the matched route
    	$this->dispatcher
    		->setRoute($matchedRoute)
    		->execute();

    	// Determine what the headers and HTTP status are and return that
    	$this->client
    		->attachDispatcher($this->dispatcher)
    		->execute();

    	return $this->client;
    }
    */