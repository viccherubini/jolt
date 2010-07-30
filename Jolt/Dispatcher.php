<?php

declare(encoding='UTF-8');
namespace Jolt;

class Dispatcher {
	
	private $route = NULL;
	private $controllerDirectory = NULL;
	
	public function __construct() {
		
	}
	
	public function __destruct() {
		
	}
	
	public function setControllerDirectory($controllerDirectory) {
		if ( empty($controllerDirectory) ) {
			throw new \Jolt\Exception('dispatcher_controller_directory_empty');
		}
		
		$this->controllerDirectory = $controllerDirectory;
		return $this;
	}
	
	public function setRoute(\Jolt\Route $route) {
		$this->route = $route;
		return $this;
	}
	
	public function getControllerDirectory() {
		return $this->controllerDirectory;
	}
	
}