<?php

declare(encoding='UTF-8');
namespace Jolt;

require_once 'Exception.php';

/**
 * This class is an entrance for building an application. It is entirely
 * static and gives you access to building new objects, starting the 
 * application, and basic CSRF protection.
 * 
 * @author vmc <vmc@leftnode.com>
 */
class Jolt {
	
	private $client = NULL;
	private $configuration = NULL;
	private $controllerLocator = NULL;
	private $dispatcher = NULL;
	private $router = NULL;
	private $view = NULL;
	
	public function __construct() {

	}
	
	public function attachClient(\Jolt\Client $client) {
		$this->client = clone $client;
		return $this;
	}
	
	public function attachConfiguration(\Jolt\Configuration $cfg) {
		$this->configuration = clone $cfg;
		return $this;
	}
	
	public function attachControllerLocator(\Jolt\Controller\Locator $locator) {
		$this->controllerLocator = clone $locator;
		return $this;
	}
	
	public function attachDispatcher(\Jolt\Dispatcher $dispatcher) {
		$this->dispatcher = clone $dispatcher;
		return $this;
	}
	
	public function attachRouter(\Jolt\Router $router) {
		$this->router = clone $router;
		return $this;
	}
	
	public function attachView(\Jolt\View $view) {
		$this->view = clone $view;
		return $this;
	}
	
	public function execute() {
		$cfg = $this->configuration;
		
		$this->view->setViewPath($cfg->viewPath)
			->setUrl($cfg->url)
			->setSecureUrl($cfg->secureUrl)
			->setUseRewrite($cfg->useRewrite);

		$route = $this->router->execute();
	
		$this->dispatcher
			->setControllerPath($cfg->controllerPath)
			->attachLocator($this->controllerLocator)
			->attachRoute($route)
			->attachView($this->view)
			->execute();

		$this->client
			->attachController($this->dispatcher->getController());
		
		return $this->client;
	}
	
	
	
}