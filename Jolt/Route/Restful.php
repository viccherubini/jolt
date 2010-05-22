<?php

declare(encoding='UTF-8');
namespace Jolt;

require_once 'Jolt/Route.php';

class Route_Restful extends Route {
	
	private $resource = NULL;
	
	public function __construct($route, $resource) {
		$this->setRoute($route)
			->setResource($resource);
	}
	
	
	public function setResource($resource) {
		$resource = trim($resource);
		if ( true === empty($resource) ) {
			throw new \Jolt\Exception('route_restful_resource_empty');
		}
		
		$this->resource = $resource;
		return $this;
	}
	
	
	
	
	public function getResource() {
		return $this->resource;
	}
	
	public function isEqual(Route $route) {
		return false;
	}
	
	public function isValid() {
		return false;
	}
	
	public function isValidUri($uri) {
		return false;
	}
}