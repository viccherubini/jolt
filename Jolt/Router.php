<?php

declare(encoding='UTF-8');
namespace Jolt;

class Router {

	private $inputVariables = array();
	private $routeList = array();
	private $uri = NULL;

	const URI_PARAM = '__u';
	
	public function __construct() {
		$this->routeList = array();
	}
	
	public function __destruct() {
		
	}
	
	public function addRoute(\Jolt\Route $route) {
		
		
	}
	
	public function setInputVariables(array $inputVariables) {
		$this->inputVariables = $inputVariables;
		return $this;
	}
	
	public function getRouteList() {
		return (array)$this->routeList;
	}


	private function extractUri() {
		if ( isset($this->inputVariables[self::URI_PARAM]) ) {
			$this->uri = $this->inputVariables[self::URI_PARAM];
		}
	}
	
}