<?php

declare(encoding='UTF-8');
namespace Jolt;

abstract class Route {

	private $route = NULL;
	private $controllerFile = NULL;

	public function __construct() {
		
	}

	public function __destruct() {
		
	}
	
	public function getControllerFile() {
		return $this->controllerFile;
	}
	
	public function getRoute() {
		return $this->route;
	}
	
	public function setControllerFile($controllerFile) {
		$this->controllerFile = trim($controllerFile);
		return $this;
	}
	
	public function setRoute($route) {
		$route = trim($route);
		if ( true === empty($route) ) {
			throw new \Jolt\Exception('route_empty');
		}
		
		$this->route = $route;
		
		return $this;
	}
	
	abstract public function isEqual(Route $route);
	
	abstract public function isValid();
	
	abstract public function isValidUri($uri);
	
}