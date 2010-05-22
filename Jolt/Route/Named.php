<?php

declare(encoding='UTF-8');
namespace Jolt;

require_once 'Jolt/Route.php';

class Route_Named extends Route {

	private $controller = NULL;
	private $action = NULL;
	
	private $argv = array();
	
	public function __construct($route, $controller, $action) {
		$this->setRoute($route)
			->setController($controller)
			->setAction($action)
			->setArgv(array());
	}
	
	public function __destruct() {
		$this->route = $this->controller = $this->action = NULL;
	}
	
	public function setAction($action) {
		$action = trim($action);
		if ( true === empty($action) ) {
			throw new \Jolt\Exception('route_named_action_empty');
		}
		
		$this->action = $action;
		
		return $this;
	}
	
	public function setArgv(array $argv) {
		$this->argv = $argv;
		return $this;
	}
	
	public function setController($controller) {
		$controller = trim($controller);
		if ( true === empty($controller) ) {
			throw new \Jolt\Exception('route_named_controller_empty');
		}
		
		$this->controller = $controller;
		
		return $this;
	}
	
	public function getAction() {
		return $this->action;
	}
	
	public function getArgv() {
		return $this->argv;
	}
	
	public function getController() {
		return $this->controller;
	}
	
	public function isEqual(Route $route) {
		return (
			$route->getRoute() === $this->getRoute() &&
			$route->getController() === $this->getController() &&
			$route->getAction() === $this->getAction()
		);
	}
	
	public function isValid() {
		$r = $this->getRoute();
		
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

		/* List of arguments to pass to the action method. */
		$argv = array();

		if ( $uri_chunk_count === $route_chunk_count ) {
			for ( $i=0; $i<$route_chunk_count; $i++ ) {
				/* ucv == uri chunk value */
				$ucv = $uri_chunk_list[$i];
				
				/* rcv = route chunk value */
				$rcv = $route_chunk_list[$i];

				if ( $ucv == $rcv ) {
					/* If the two are exactly the same, no expansion is needed. */
					$matched_chunk_count++;
				} else {
					/**
					 * More investigation is required. See if there is a % character followed by a (n|s), and if so, expand it.
					 * A limitation is that only a single % replacement can exist in a chunk at once, for now.
					 * 
					 * @todo Allow multiple %n or %s characters in a chunk at once.
					 */
					$offset = stripos($rcv, '%');
					if ( false !== $offset && true === isset($rcv[$offset+1]) ) {
						$rcv_type = $rcv[$offset+1];
						$rcv_length = strlen($rcv);
						
						if ( 0 !== $offset ) {
							$ucv = trim(substr_replace($ucv, NULL, 0, $offset));
						}
						
						if ( ($offset+2) < $rcv_length ) {
							$goto = strlen(substr($rcv, $offset+2));
							$ucv = substr_replace($ucv, NULL, -$goto);
						}
						
						/* Now that we have the correct $ucv values, let's make sure they're types are correct. */
						$matched = false;
						
						switch ( $rcv_type ) {
							case 'n': {
								$matched = is_numeric($ucv);
								break;
							}
							
							case 's': {
								$matched = is_string($ucv) && !is_numeric($ucv);
								break;
							}
						}
						
						if ( true === $matched ) {
							$matched_chunk_count++;
							$argv[$rcv] = $ucv;
						}
					}
				}
			}
		}
		
		$this->setArgv($argv);
	
		return ( $matched_chunk_count === $route_chunk_count );
	}
}