<?php

declare(encoding='UTF-8');
namespace Jolt\Route;

use \Jolt\Route;

class Named extends Route {

	private $action = NULL;
	private $controller = NULL;
	private $requestMethod = NULL;
	
	private $argv = array();
	private $requestMethods = array();
	
	public function __construct($requestMethod, $route, $controller, $action) {
		$this->requestMethods = array('GET' => true, 'POST' => true, 'PUT' => true, 'DELETE' => true);
		
		$this->setRequestMethod($requestMethod)
			->setRoute($route)
			->setController($controller)
			->setAction($action)
			->setArgv(array());
	}
	
	public function __destruct() {
		$this->route = $this->controller = $this->action = NULL;
	}
	
	public function setAction($action) {
		$action = ( !is_string($action) ? NULL : trim($action) );
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
		$controller = ( !is_string($controller) ? NULL : trim($controller) );
		if ( true === empty($controller) ) {
			throw new \Jolt\Exception('route_named_controller_empty');
		}
		
		$this->controller = $controller;
		
		return $this;
	}
	
	public function setRequestMethod($requestMethod) {
		$requestMethod = ( !is_string($requestMethod) ? NULL : strtoupper(trim($requestMethod)) );
		if ( !isset($this->requestMethods[$requestMethod]) ) {
			throw new \Jolt\Exception('route_method_is_not_valid');
		}
		
		$this->requestMethod = $requestMethod;
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
	
	public function getRequestMethod() {
		return $this->requestMethod;
	}
	
	public function isEqual(Route $route) {
		return (
			$route instanceof \Jolt\Route\Named &&
			$route->getRequestMethod() === $this->getRequestMethod() &&
			$route->getRoute() === $this->getRoute() &&
			$route->getController() === $this->getController() &&
			$route->getAction() === $this->getAction()
		);
	}
	
	public function isValid() {
		$route = $this->getRoute();
		
		/* Special case of a valid route. */
		if ( '/' == $route ) {
			return true;
		}
		
		/**
		 * Jolt is more restrictive about routes than the normal Internet RFC
		 * standards. This is to keep them clean, legible and readible.
		 * 
		 * @see tests/Jolt/Route/Route/NamedTest.php
		 */
		if ( 0 === preg_match('#^/([a-z]+)([a-z0-9_\-/%\.]*)$#i', $route) ) {
			return false;
		}
		
		return true;
	}
	
	public function isValidUri($uri) {
		// Remove the beginning / from the URI and route.
		$uri = ltrim($uri, '/');
		$uriChunkList = explode('/', $uri);
		$uriChunkCount = count($uriChunkList);
		
		$route = $this->getRoute();
		$route = ltrim($route, '/');
		$routeChunkList = explode('/', $route);
		$routeChunkCount = count($routeChunkList);
		
		// If all of the chunks eventually match, we have a matched route.
		$matchedChunkCount = 0;

		// List of arguments to pass to the action method.
		$argv = array();

		if ( $uriChunkCount === $routeChunkCount ) {
			for ( $i=0; $i<$routeChunkCount; $i++ ) {
				// ucv == uri chunk value
				$ucv = $uriChunkList[$i];
				
				// rcv = route chunk value
				$rcv = $routeChunkList[$i];

				if ( $ucv == $rcv ) {
					// If the two are exactly the same, no expansion is needed.
					$matchedChunkCount++;
				} else {
					/**
					 * More investigation is required. See if there is a % character followed by a (n|s), and if so, expand it.
					 * A limitation is that only a single % replacement can exist in a chunk at once, for now.
					 * 
					 * @todo Allow multiple %n or %s characters in a chunk at once.
					 */
					$offset = stripos($rcv, '%');
					if ( false !== $offset && true === isset($rcv[$offset+1]) ) {
						$rcvType = $rcv[$offset+1];
						$rcvLength = strlen($rcv);
						
						if ( 0 !== $offset ) {
							$ucv = trim(substr_replace($ucv, NULL, 0, $offset));
						}
						
						if ( ($offset+2) < $rcvLength ) {
							$goto = strlen(substr($rcv, $offset+2));
							$ucv = substr_replace($ucv, NULL, -$goto);
						}
						
						// Now that we have the correct $ucv values, let's make sure they're types are correct.
						$matched = false;
						
						switch ( $rcvType ) {
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
							$matchedChunkCount++;
							$argv[$rcv] = $ucv;
						}
					}
				}
			}
		}
		
		$this->argv = $argv;
	
		return ( $matchedChunkCount === $routeChunkCount );
	}
}