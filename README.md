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

Jolt is hosted at [Joltcore.org](http://joltcore.org). Jolt bugs can be found at [http://bugs.joltcore.org](http://bugs.joltcore.org).

## Team Members
* Vic Cherubini <vmc@leftnode.com>

## Sponsored By
* Leftnode Software, Inc. <http://leftnodesoftware.com>

## A Sample Jolt Application
    <?php
    
    declare(encoding='UTF-8');
    namespace JoltApp;
    
    use \Jolt\Client,
      \Jolt\Configuration,
      \Jolt\Controller,
      \Jolt\Controller\Locator,
      \Jolt\Dispatcher,
      \Jolt\Jolt,
      \Jolt\Registry,
      \Jolt\Route,
      \Jolt\Route\Named,
      \Jolt\Route\Named\Get as NamedGet,
      \Jolt\Route\Named\Post as NamedPost,
      \Jolt\Router,
      \Jolt\View;

    require_once 'Jolt/Framework.php';
    require_once 'Jolt/Registry.php';
    require_once 'Jolt/Route.php';
    require_once 'Jolt/Route/Named.php';
    require_once 'Jolt/Route/Named/Get.php';
    require_once 'Jolt/Route/Named/Post.php';

    try {
    
      $jolt = new Jolt;

      // Configuration
      $cfg = new Configuration;
      $cfg->controllerPath = 'private/controllers/';
      $cfg->cssPath = 'public/css/';
      $cfg->jsPath = 'public/js/';
      $cfg->imagePath = 'public/images/';
      $cfg->routeParameter = '__u';
      $cfg->secureUrl = 'https://joltwebsite.com';
      $cfg->url = 'http://joltwebsite.com';
      $cfg->useRewrite = true;
      $cfg->viewPath = 'private/views/';

      // Configure the routes and router, of course, this could be pushed to another file
      $router = new Router;
      $router->setParameters($_GET)
        ->setRequestMethod($_SERVER['REQUEST_METHOD'])
        ->setHttp404Route(new NamedGet('/', 'JoltApp\\NotFound', 'error404'));

      // GET routes
      $router->addRoute(new NamedGet('/', 'JoltApp\\Index', 'indexAction'))
        ->addRoute(new NamedGet('/index', 'JoltApp\\Index', 'indexAction'))
        ->addRoute(new NamedGet('/register', 'JoltApp\\Index', 'registerAction'))
        ->addRoute(new NamedGet('/logout', 'JoltApp\\Index', 'logoutAction'))
        ->addRoute(new NamedGet('/dashboard', 'JoltApp\\Dashboard', 'indexAction'));

      // POST routes
      $router->addRoute(new NamedPost('/login', 'JoltApp\\Index', 'loginAction'))
        ->addRoute(new NamedPost('/register', 'JoltApp\\Index', 'registerAccountAction'));

      $jolt->attachClient(new Client)
        ->attachConfiguration($cfg)
        ->attachControllerLocator(new Locator)
        ->attachDispatcher(new Dispatcher)
        ->attachRouter($router)
        ->attachView(new View);
  
      echo $jolt->execute();

    } catch ( \Jolt\Exception $e ) {
      echo '<pre>', $e, '</pre>';
      exit;
    }