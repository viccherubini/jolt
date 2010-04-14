<?php

abstract class Jolt_Route {

	private $route = NULL;

	public function __construct() {
		
	}

	public function __destruct() {
		
	}
	
	public function setRoute($route) {
		$route = trim($route);
		if ( true === empty($route) ) {
			throw new Jolt_Exception('route_empty');
		}
		
		$this->route = $route;
		
		return $this;
	}
	
	public function getRoute() {
		return $this->route;
	}
	
	abstract public function isValid();
}