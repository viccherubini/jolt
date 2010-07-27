<?php

declare(encoding='UTF-8');
namespace Jolt;

class Router {

	private $requestMethod = NULL;
	private $routeParameter = '__u';
	
	private $parameters = array();
	private $routeList = array();
	
	public function __construct() {
		$this->routeList = array();
		$this->setRequestMethod('GET');
	}
	
	public function __destruct() {
		unset($this->routeList);
	}
	
	public function addRoute(\Jolt\Route $route) {
		$routeExists = false;
		array_walk($this->routeList, function ($v, $k) use ($route, &$routeExists) {
			if ( $v->isEqual($route) ) {
				$routeExists = true;
			}
		});
		
		if ( $routeExists ) {
			throw new \Jolt\Exception('router_route_exists');
		}
		
		$this->routeList[] = $route;
		
		return $this;
	}
	
	public function execute() {
		if ( 0 === count($this->routeList) ) {
			throw new \Jolt\Exception('router_no_routes');
		}
		
		$path = $this->extractPath();
		if ( empty($path) ) {
			throw new \Jolt\Exception('router_no_path_found');
		}
		
		$matchedRoute = NULL;
		array_walk($this->routeList, function($route, $k) use ($path, &$matchedRoute) {
			if ( $route->isValidUri($path) ) {
				
				
			}
		});
	}
	
	public function setParameters(array $parameters) {
		$this->parameters = $parameters;
		return $this;
	}
	
	public function setRequestMethod($requestMethod) {
		$this->requestMethod = strtoupper(trim($requestMethod));
		return $this;
	}
	
	public function getRequestMethod() {
		return $this->requestMethod;
	}
	
	public function getRouteList() {
		return (array)$this->routeList;
	}
	
	public function getRouteParameter() {
		return $this->routeParameter;
	}

	private function extractPath() {
		$path = NULL;
		if ( isset($this->parameters[$this->routeParameter]) ) {
			$path = $this->parameters[$this->routeParameter];
		}
		return $path;
	}
	
}