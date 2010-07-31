<?php

declare(encoding='UTF-8');
namespace Jolt;

use \Jolt\Controller\Locator as Locator;

require_once 'Jolt/Controller/Locator.php';

class Dispatcher {
	
	private $controllerDirectory = NULL;
	private $route = NULL;
	private $view = NULL;
	
	public function __construct() {
		
	}
	
	public function __destruct() {
		
	}
	
	public function attachRoute(\Jolt\Route $route) {
		$this->route = clone $route;
		return $this;
	}
	
	public function attachView(\Jolt\View $view) {
		$this->view = clone $view;
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
		
		try {
			$controller = Locator::load($this->controllerDirectory, $this->route->getController());
			
		} catch ( \Jolt\Exception $e ) {
			throw new \Jolt\Exception('dispatcher_controller_missing');
		}
	}
	
	public function setControllerDirectory($dir) {
		if ( empty($dir) ) {
			throw new \Jolt\Exception('dispatcher_controller_directory_empty');
		}
		
		$dirLength = strlen($dir);
		if ( $dir[$dirLength-1] != DIRECTORY_SEPARATOR ) {
			$dir .= DIRECTORY_SEPARATOR;
		}
		
		$this->controllerDirectory = $dir;
		return $this;
	}
	
	public function getControllerDirectory() {
		return $this->controllerDirectory;
	}
	
}