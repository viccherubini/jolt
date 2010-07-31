<?php

declare(encoding='UTF-8');
namespace Jolt;

class Dispatcher {
	
	private $controllerDirectory = NULL;
	private $route = NULL;
	private $view = NULL;
	
	public function __construct() {
		
	}
	
	public function __destruct() {
		
	}
	
	public function attachRoute(\Jolt\Route $route) {
		$this->route = $route;
		return $this;
	}
	
	public function attachView(\Jolt\View $view) {
		$this->view = $view;
		return $this;
	}
	
	public function execute() {
		if ( !is_dir($this->controllerDirectory) ) {
			throw new \Jolt\Exception('dispatcher_controller_directory_does_not_exist');
		}
		
		if ( is_null($this->route) ) {
			throw new \Jolt\Exception('dispatcher_route_is_null');
		}
		
		if ( is_null($this->view) ) {
			throw new \Jolt\Exception('dispatcher_view_is_null');
		}
		
		$controller = $this->route->getController();
		$controllerFile = $this->controllerDirectory . $controller . '.php';
		
		if ( !is_file($controllerFile) ) {
			throw new \Jolt\Exception('dispatcher_controller_file_does_not_exist');
		}
		
	}
	
	public function setControllerDirectory($controllerDirectory) {
		if ( empty($controllerDirectory) ) {
			throw new \Jolt\Exception('dispatcher_controller_directory_empty');
		}
		
		$len = strlen($controllerDirectory);
		if ( $controllerDirectory[$len-1] != DIRECTORY_SEPARATOR ) {
			$controllerDirectory .= DIRECTORY_SEPARATOR;
		}
		
		$this->controllerDirectory = $controllerDirectory;
		return $this;
	}
	
	public function getControllerDirectory() {
		return $this->controllerDirectory;
	}
	
}