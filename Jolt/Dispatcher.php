<?php

class Jolt_Dispatcher {
	
	private $application_path = NULL;
	
	private $controller_path = NULL;
	
	private $layout_path = NULL;
	
	private $route = NULL;
	
	public function __construct() {
		
	}
	
	public function dispatch() {
		$route = $this->getRoute();
		if ( NULL === $route ) {
			throw new Jolt_Exception('dispatcher_route_is_null');
		}
		
		$controller_file = $route->getControllerFile();
		lib_throw_if(empty($controller_file), 'dispatcher_route_controller_file_is_null');
		
		$application_path = $this->getApplicationPath();
		lib_throw_if(empty($application_path), 'dispatcher_application_path_is_null');
		
		$controller_path = $this->getControllerPath();
		lib_throw_if(empty($application_path), 'dispatcher_controller_path_is_null');
		
		$ds = DIRECTORY_SEPARATOR;
		$execution_path = $application_path . $ds . $controller_path . $ds . $controller_file;
		
		
	}
	
	
	
	public function getApplicationPath() {
		return $this->application_path;
	}
	
	public function getControllerPath() {
		return $this->controller_path;
	}
	
	public function getLayoutPath() {
		return $this->layout_path;
	}
	
	public function getRoute() {
		return $this->route;
	}
	
	
	
	
	public function setApplicationPath($application_path) {
		$this->application_path = rtrim($application_path, DIRECTORY_SEPARATOR);
		return $this;
	}
	
	public function setControllerPath($controller_path) {
		$this->controller_path = rtrim($controller_path, DIRECTORY_SEPARATOR);
		return $this;
	}
	
	public function setLayoutPath($layout_path) {
		$this->layout_path = rtrim($layout_path, DIRECTORY_SEPARATOR);
		return $this;
	}
	
	public function setRoute(Jolt_Route $route) {
		$this->route = $route;
		return $this;
	}
	
	
}