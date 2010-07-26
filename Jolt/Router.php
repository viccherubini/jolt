<?php

declare(encoding='UTF-8');
namespace Jolt;

class Router {

	private $requestMethod = NULL;
	private $uri = NULL;
	private $uriParam = '__';
	
	private $inputVariables = array();
	private $routeList = array();
	
	public function __construct() {
		$this->routeList = array();
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
		
	}
	
	public function setInputVariables(array $inputVariables) {
		$this->inputVariables = $inputVariables;
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

	private function extractUri() {
		if ( isset($this->inputVariables[$this->uriParam]) ) {
			$this->uri = $this->inputVariables[$this->uriParam];
		}
	}
	
}