<?php

declare(encoding='UTF-8');
namespace Jolt;

use \Jolt\Controller\Locator as Locator;

require_once 'Jolt/Controller/Locator.php';

class Dispatcher {
	
	private $controllerPath = NULL;
	private $locator = NULL;
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
		
		if ( is_null($this->locator) ) {
			throw new \Jolt\Exception('dispatcher_locator_is_null');
		}
		
		if ( is_null($this->route) ) {
			throw new \Jolt\Exception('dispatcher_route_is_null');
		}
		
		if ( is_null($this->view) ) {
			throw new \Jolt\Exception('dispatcher_view_is_null');
		}
		
		try {
			$controller = $this->locator->load($this->controllerPath, $this->route->getController());
		
			// Call $controller->execute($this->route->getArgv());
			// Which returns the rendered controller into a variable
			// Store that variable internally
			// Also attach the view to the Controller
			// That way when attaching a Dispatcher to a Client, the Client can get the data.
			
		} catch ( \Jolt\Exception $e ) {
			throw new \Jolt\Exception('dispatcher_controller_missing');
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
	
}