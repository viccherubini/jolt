<?php

declare(encoding='UTF-8');
namespace Jolt\Route;

use \Jolt\Route;

class Restful extends Route {
	
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
		return (
			$route instanceof \Jolt\Route\Restful &&
			$this->getRoute() === $route->getRoute() &&
			$this->getResource() === $route->getResource()
		);
	}
	
	public function isValid() {
		$route = $this->getRoute();
		
		/* Special case of a valid route. */
		if ( '/' == $route ) {
			return true;
		}
		
		if ( 0 === preg_match('#^/([a-z]+)([a-z0-9_\-]*)$#i', $route) ) {
			return false;
		}
		
		return true;
	}
	
	public function isValidUri($uri) {
		$route = trim($this->getRoute());
		return ( $route === trim($uri));
	}
	
}