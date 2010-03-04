<?php

class Router {

	private $named_list = array();
	private $restful_list = array();

	public function __construct() {
		$this->reset();
	}
	
	public function __destruct() {
		
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
