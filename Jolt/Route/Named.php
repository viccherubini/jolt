<?php

require_once 'Jolt/Route.php';

class Jolt_Route_Named extends Jolt_Route {

	private $controller = NULL;
	private $action = NULL;
	
	
	public function __construct($route, $controller, $action) {
		$this->setRoute($route)
			->setController($controller)
			->setAction($action);
	}
	
	public function __destruct() {
		$this->route = $this->controller = $this->action = NULL;
	}
	
	public function setController($controller) {
		$controller = trim($controller);
		if ( true === empty($controller) ) {
			throw new Jolt_Exception('route_named_controller_empty');
		}
		
		$this->controller = $controller;
		
		return $this;
	}
	
	public function setAction($action) {
		$action = trim($action);
		if ( true === empty($action) ) {
			throw new Jolt_Exception('route_named_action_empty');
		}
		
		$this->action = $action;
		
		return $this;
	}

	public function getController() {
		return $this->controller;
	}
	
	public function getAction() {
		return $this->action;
	}
	
	
	public function isValid() {
		$r = $this->getRoute();
		
		if ( true === empty($r) ) {
			return false;
		}
		
		/* Special case of a valid route. */
		if ( '/' == $r ) {
			return true;
		}
		
		/**
		 * Jolt is more restrictive about routes than the normal Internet RFC
		 * standards. This is to keep them clean, legible and readible.
		 * 
		 * @see Tests/JoltCore/Route/Route/NamedTest.php
		 */
		if ( 0 === preg_match('#^/([a-z]+)([a-z0-9_\-/%\.]*)$#i', $r) ) {
			return false;
		}
		
		return true;
	}
}