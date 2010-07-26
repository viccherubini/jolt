<?php

declare(encoding='UTF-8');
namespace Jolt;

class Router {

	private $config = array();
	private $routeList = array();
	private $uri = array();
	
	const URI_PARAM = '__u';
	
	public function __construct() {
		$this->routeList = array();
	}
	
	public function __destruct() {
		
	}
	
	
	

	
	public function getRouteList() {
		return (array)$this->routeList;
	}
	
	

}