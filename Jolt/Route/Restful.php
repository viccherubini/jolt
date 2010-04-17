<?php

require_once 'Jolt/Route.php';

class Jolt_Route_Restful extends Jolt_Route {
	
	private $resource = NULL;
	
	public function __construct($route, $resource) {
		$this->setRoute($route)
			->setResource($resource);
	}
	
	
	public function setResource($resource) {
		$resource = trim($resource);
		if ( true === empty($resource) ) {
			throw new Jolt_Exception('route_restful_resource_empty');
		}
		
		$this->resource = $resource;
		return $this;
	}
	
	
	
	
	public function getResource() {
		return $this->resource;
	}
	
	public function isValid() {
		return false;
	}
}