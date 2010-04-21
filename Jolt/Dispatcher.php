<?php

class Jolt_Dispatcher {
	
	private $controller_path = NULL;
	
	private $route = NULL;
	
	public function __construct() {
		
	}
	
	public function dispatch() {
		$route = $this->getRoute();
		if ( NULL === $route ) {
			throw new Jolt_Exception('dispatcher_route_is_null');
		}
		
		
	}
	
	public function getControllerPath() {
		return $this->controller_path;
	}
	
	public function getRoute() {
		return $this->route;
	}
	
	
	
	
	
	public function setControllerPath($controller_path) {
		$this->controller_path = trim($controller_path);
		return $this;
	}
	
	public function setRoute(Jolt_Route $route) {
		$this->route = $route;
		return $this;
	}
	
	
}