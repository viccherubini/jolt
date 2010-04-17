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
		 * @see tests/Jolt/Route/Route/NamedTest.php
		 */
		if ( 0 === preg_match('#^/([a-z]+)([a-z0-9_\-/%\.]*)$#i', $r) ) {
			return false;
		}
		
		return true;
	}
	
	public function isValidUri($uri) {
		/* Remove the beginning / from the URI and route. */
		$uri = ltrim($uri, '/');
		$uri_chunk_list = explode('/', $uri);
		$uri_chunk_count = count($uri_chunk_list);
		
		$route = $this->getRoute();
		$route = ltrim($route, '/');
		$route_chunk_list = explode('/', $route);
		$route_chunk_count = count($route_chunk_list);
		
		/* If all of the chunks eventually match, we have a matched route. */
		$matched_chunk_count = 0;
		
		if ( $uri_chunk_list === $route_chunk_list ) {
			for ( $i=0; $i<$uri_chunk_count; $i++ ) {
				/* ucv == uri chunk value */
				$ucv = $uri_chunk_list[$i];
				
				/* rcv = route chunk value */
				$rcv = $route_chunk_list[$i];
				
				if ( $ucv === $rcv ) {
					/* If the two are exactly the same, no expansion is needed. */
					$matched_chunk_count++;
				} else {
					/**
					 * More investigation is required. See if there is a % character followed by a (d|s|f), and if so, expand it.
					 * A limitation is that only a single % replacement can exist in a chunk at once, for now.
					 */
					if ( 1 === preg_match('/\%(d|s|f)/i', $rcv) ) {
						/* Strip out everything around the %(d|s|f). */
						
						
					}
				}
			}
		}
	
		return ( $matched_chunk_count === $route_chunk_count );
	}
}