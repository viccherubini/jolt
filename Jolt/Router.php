<?php

declare(encoding='UTF-8');
namespace Jolt;

class Router {

	private $inputVariables = array();
	private $routeList = array();
	private $uri = NULL;
	private $uriParam = '__';
	
	public function __construct() {
		$this->routeList = array();
	}
	
	public function __destruct() {
		
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
	
	public function setInputVariables(array $inputVariables) {
		$this->inputVariables = $inputVariables;
		return $this;
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