<?php

require_once 'Exception.php';

class Jolt_Router {

	private $config = array();
	private $named_list = array();
	private $restful_list = array();
	
	private $uri = array();
	
	const URI_PARAM = '_u';
	const ROUTE_REPLACE_CHAR = '%';
	
	public function __construct() {
		$this->reset();
	}
	
	public function __destruct() {
		
	}
	
	
	public function execute() {
		$named_list = $this->getNamedRouteList();
		$restful_list = $this->getRestfulRouteList();
		
		if ( 0 === $this->getRouteCount() ) {
			throw new Jolt_Exception('router_lists_empty');
		}
		
		$this->parseUri();
		
		$uri = $this->getUri();
		
		
		
		foreach ( $named_list as $named_route ) {
			if ( true === $named_route->isValidUri($uri) ) {
				/* Get a Jolt_Dispatcher and dispatch the route. */
				
			}
		}
		
	}
	
	
	
	
	
	public function getConfig() {
		return (array)$this->config;
	}
	
	public function getNamedRouteList() {
		return (array)$this->named_list;
	}
	
	public function getRestfulRouteList() {
		return (array)$this->restful_list;
	}
	
	public function getRouteCount() {
		return ( count($this->getNamedRouteList()) + count($this->getRestfulRouteList()) );
	}
	
	public function getUri() {
		return $this->uri;
	}
	
	
	public function setConfig(array $config) {
		if ( 0 === count($config) ) {
			throw new Jolt_Exception('router_config_empty');
		}
		
		/**
		 * Ensure the fields below are in the config array.
		 * No other fields should be present.
		 */
		$required_fields = array(
			'site_root' => true,
			'site_root_secure' => true,
			'app_dir' => true,
			'layout_dir' => true,
			'default_layout' => true,
			'rewrite' => true
		);
		
		$field_difference = array_diff_key($required_fields, $config);
		
		if ( count($field_difference) > 0 ) {
			throw new Jolt_Exception('router_config_malformed');
		}
		
		$this->config = $config;
		return $this;
	}
	
	public function setNamedRouteList(array $named_list) {
		if ( 0 === count($named_list) ) {
			throw new Jolt_Exception('router_named_list_empty');
		}
		
		foreach ( $named_list as $route ) {
			if ( false === is_object($route) ) {
				$type_name = gettype($route);
				throw new Jolt_Exception("router_named_list_element_not_object: '{$type_name}'");
			}
			
			if ( false === $route instanceof Jolt_Route_Named ) {
				$class_name = get_class($route);
				throw new Jolt_Exception("router_named_list_element_not_named_route: '{$class_name}'");
			}
		}
		
		if ( false === $this->isRouteListUnqiue($named_list) ) {
			throw new Jolt_Exception("router_named_list_duplicate_route");
		}
		
		$this->named_list = $named_list;
		return $this;
	}
	
	public function setRestfulRouteList(array $restful_list) {
		if ( 0 === count($restful_list) ) {
			throw new Jolt_Exception("router_restful_list_empty");
		}
		
		foreach ( $restful_list as $route ) {
			if ( false === is_object($route) ) {
				$type_name = gettype($route);
				throw new Jolt_Exception("router_restful_list_element_not_object: '{$type_name}'");
			}
			
			if ( false === $route instanceof Jolt_Route_Restful ) {
				$class_name = get_class($route);
				throw new Jolt_Exception("router_restful_list_element_not_named_route: '{$class_name}'");
			}
		}
		
		if ( false === $this->isRouteListUnqiue($restful_list) ) {
			throw new Jolt_Exception("router_restful_list_duplicate_route");
		}
		
		$this->restful_list = $restful_list;
		return $this;
	}
	
	public function setUri($uri) {
		/* Add a / to the first character of the URI if it isn't already there. Routes require them. */
		if ( '/' !== $uri{0} ) {
			$uri = "/{$uri}";
		}
		
		$this->uri = $uri;
		return $this;
	}
	
	
	
	
	
	
	
	
	
	
	private function reset() {
		$this->named_list = array();
		$this->restful_list = array();
	}
	
	
	
	private function parseUri() {
		$uri = er(self::URI_PARAM, $REQUEST);
	
		/* Special case. */
		if ( true === empty($uri) ) {
			$uri = '/';
		}
		
		$this->setUri($uri);
		
		return true;
	}
	
	private function isRouteListUnqiue(array $route_list) {
		$route_uri_list = array();
		
		foreach ( $route_list as $route ) {
			$route_uri_list[] = $route->getRoute();
		}
		
		$route_uri_list_unique = array_unique($route_uri_list);
		
		return ( count($route_uri_list) === count($route_uri_list_unique) );
	}
}