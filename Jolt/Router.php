<?php

declare(encoding='UTF-8');
namespace Jolt;

require_once 'Exception.php';

class Router {

	private $named_list = array();
	private $restful_list = array();

	public function __construct() {
		$this->reset();
	}
	
	public function __destruct() {
		
	}
	
	public function setNamedRouteList(array $named_list) {
		if ( 0 === count($named_list) ) {
			throw new Jolt_Exception('router_named_list_empty');
		}
		
		array_walk($named_list, function(&$v, $k) {
			if ( false === $v instanceof Jolt_Route ) {
				throw new \Jolt\Exception('router_named_list_element_not_route');
			}
		});
	}
	
	public function setRestfulRouteList(array $restful_list) {
		
	}
	
	public function getNamedRouteList() {
		return (array)$this->named_list;
	}
	
	public function getRestfulRouteList() {
		return (array)$this->restful_list;
	}
	
	private function reset() {
		$this->named_list = array();
		$this->restful_list = array();
	}
}