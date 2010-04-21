<?php

abstract class Jolt_Route {

	private $route = NULL;
	private $controller_file = NULL;

	public function __construct() {
		
	}

	public function __destruct() {
		
	}
	
	
	public function getControllerFile() {
		return $this->controller_file;
	}
	
	public function getRoute() {
		return $this->route;
	}
	
	
	
	public function setControllerFile($controller_file) {
		$this->controller_file = trim($controller_file);
		return $this;
	}
	
	public function setRoute($route) {
		$route = trim($route);
		if ( true === empty($route) ) {
			throw new Jolt_Exception('route_empty');
		}
		
		$this->route = $route;
		
		return $this;
	}
	
	
	
	abstract public function isEqual(Jolt_Route $route);
	
	abstract public function isValid();
	
	abstract public function isValidUri($uri);
}