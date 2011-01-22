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
		if ( empty($resource) ) {
			throw new \Jolt\Exception('RESTful resource is empty and not valid.');
		}
		
		$this->resource = $resource;
		return $this;
	}
	
	public function getResource() {
		return $this->resource;
	}
	
	public function getRequestMethod() {
		return $this->resource;
	}
	
	public function is_equal(Route $route) {
		return (
			$route instanceof \Jolt\Route\Restful &&
			$this->getRoute() === $route->getRoute() &&
			$this->getResource() === $route->getResource()
		);
	}
	
	public function is_valid() {
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
	
	public function is_valid_path($uri) {
		$route = trim($this->getRoute());
		return ( $route === trim($uri));
	}
	
}
