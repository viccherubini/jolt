<?php

declare(encoding='UTF-8');
namespace Jolt;

use \Jolt\Controller\Locator as Locator;

require_once 'Jolt/Controller/Locator.php';

class Dispatcher {
	
	private $controller = NULL;
	private $controllerPath = NULL;
	private $locator = NULL;
	private $renderedController = NULL;
	private $route = NULL;
	private $view = NULL;
	
	public function __construct() {
		
	}
	
	public function __destruct() {
		
	}
	
	public function attachLocator(\Jolt\Controller\Locator $locator) {
		$this->locator = clone $locator;
		return $this;
	}
	
	public function attachRoute(\Jolt\Route $route) {
		$this->route = clone $route;
		return $this;
	}
	
	public function attachView(\Jolt\View $view) {
		$this->view = clone $view;
		return $this;
	}
	
	public function execute() {
		if ( !is_dir($this->controllerPath) ) {
			throw new \Jolt\Exception('dispatcher_controller_path_does_not_exist');
		}
		
		$locator = $this->checkLocator();
		$route = $this->checkRoute();
		$view = $this->checkView();
		
		try {

			$controller = $this->locator->load($this->controllerPath, $route->getController());
			$controller->attachView($view);
			$controller->setAction($route->getAction());
			$controller->execute($route->getArgv());
			
			$this->controller = $controller;
			
		} catch ( \Jolt\Exception $e ) {
			throw new \Jolt\Exception("dispatcher_controller_missing: {$e->getMessage()}");
		}
		
		return true;
	}
	
	public function setControllerPath($controllerPath) {
		if ( empty($controllerPath) ) {
			throw new \Jolt\Exception('dispatcher_controller_path_empty');
		}
		
		$pathLength = strlen($controllerPath);
		if ( $controllerPath[$pathLength-1] != DIRECTORY_SEPARATOR ) {
			$controllerPath .= DIRECTORY_SEPARATOR;
		}
		
		$this->controllerPath = $controllerPath;
		return $this;
	}
	
	public function getControllerPath() {
		return $this->controllerPath;
	}
	
	public function getController() {
		return $this->controller;
	}
	
	private function checkLocator() {
		if ( is_null($this->locator) ) {
			throw new \Jolt\Exception('dispatcher_locator_is_null');
		}
		
		return $this->locator;
	}
	
	private function checkRoute() {
		if ( is_null($this->route) ) {
			throw new \Jolt\Exception('dispatcher_route_is_null');
		}
		
		return $this->route;
	}
	
	private function checkView() {
		if ( is_null($this->view) ) {
			throw new \Jolt\Exception('dispatcher_view_is_null');
		}
		
		return $this->view;
	}
}