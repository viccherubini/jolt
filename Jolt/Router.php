<?php

declare(encoding='UTF-8');
namespace Jolt;

class Router {

	private $config = array();
	private $route_list = array();
	private $uri = array();
	
	const URI_PARAM = '_u';
	const ROUTE_REPLACE_CHAR = '%';
	
	public function __construct() {
		$this->route_list = array();
	}
	
	public function __destruct() {
		
	}
	
	
	public function execute(\Jolt\Dispatcher $dispatcher) {
		$route_list = $this->getRouteList();
		
		if ( 0 === $this->getRouteCount() ) {
			throw new \Jolt\Exception('router_route_list_empty');
		}
		
		$this->parseUri();
		
		$uri = $this->getUri();
		
		$matched_route = NULL;
		
		foreach ( $route_list as $route ) {
			if ( true === $route->isValidUri($uri) ) {
				// Set the route, break, and execute it with the dispatcher.
				$matched_route = $route;
				break;
			}
		}
		
		if ( NULL === $matched_route ) {
			throw new \Jolt\Exception('router_no_matched_route');
		}
		
		//$dispatcher->setRoute($matched_route);
		//$dispatcher->dispatch();
		
	}
	

	public function getConfig() {
		return (array)$this->config;
	}

	
	public function getRouteList() {
		return (array)$this->route_list;
	}
	
	
	public function getRouteCount() {
		return count($this->getRouteList());
	}

	
	public function getUri() {
		return $this->uri;
	}
	
	
	public function setConfig(array $config) {
		if ( 0 === count($config) ) {
			throw new \Jolt\Exception('router_config_empty');
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
			throw new \Jolt\Exception('router_config_malformed');
		}
		
		$this->config = $config;
		return $this;
	}

	
	public function setRouteList(array $route_list) {
		array_walk($route_list, function($v, $k) {
			if ( false === $v instanceof \Jolt\Route_Named && false === $v instanceof \Jolt\Route_Restful ) {
				throw new \Jolt\Exception("router_named_list_element_not_valid_route");
			}
		});
		
		if ( false === $this->isRouteListEntirelyUnqiue($route_list) ) {
			throw new \Jolt\Exception("router_named_list_duplicate_route");
		}
		
		$this->route_list = $route_list;
		return $this;
	}
	
	
	public function setUri($uri) {
		/* Add a / to the first character of the URI if it isn't already there. Routes require them. */
		if ( '/' !== $uri[0] ) {
			$uri = "/{$uri}";
		}
		
		$this->uri = $uri;
		return $this;
	}
	
	
	
	
	private function parseUri() {
		$uri = er(self::URI_PARAM, $_REQUEST);
	
		/* Special case. */
		if ( true === empty($uri) ) {
			$uri = '/';
		}
		
		$this->setUri($uri);
		
		return true;
	}
	
	
	private function isRouteListEntirelyUnqiue(array $route_list) {
		$route_uri_list = array();
		
		foreach ( $route_list as $route ) {
			$route_uri_list[] = $route->getRoute();
		}
		
		$route_uri_list_unique = array_unique($route_uri_list);
		
		return ( count($route_uri_list) === count($route_uri_list_unique) );
	}
}