<?php

declare(encoding='UTF-8');
namespace Jolt;

require_once 'Exception.php';

class Router {

	private $config = array();
	private $named_list = array();
	private $restful_list = array();
	
	
	const URI_PARAM = '_u';
	
	public function __construct() {
		$this->reset();
	}
	
	public function __destruct() {
		
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
	
	public function setNamedRouteList(array $named_list) {
		if ( 0 === count($named_list) ) {
			throw new \Jolt\Exception('router_named_list_empty');
		}
		
		array_walk($named_list, function(&$v, $k) {
			if ( false === is_object($v) ) {
				$type_name = gettype($v);
				throw new \Jolt\Exception("router_named_list_element_not_object: '{$type_name}'");
			}
			
			if ( false === $v instanceof \Jolt\Route_Named ) {
				$class_name = get_class($v);
				throw new \Jolt\Exception("router_named_list_element_not_named_route: '{$class_name}'");
			}
		});
		
		$this->named_list = $named_list;
		return $this;
	}
	
	public function setRestfulRouteList(array $restful_list) {
		if ( 0 === count($restful_list) ) {
			throw new \Jolt\Exception('router_restful_list_empty');
		}
		
		array_walk($restful_list, function(&$v, $k) {
			if ( false === $v instanceof \Jolt\Route_Restful ) {
				$class_name = get_class($v);
				throw new \Jolt\Exception("router_restful_list_element_not_restful_route: '{$class_name}'");
			}
		});
		
		$this->restful_list = $restful_list;
		return $this;
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
	
	
	
	public function dispatch() {
		$named_list = $this->getNamedRouteList();
		$restful_list = $this->getRestfulRouteList();
		
		if ( 0 === count($named_list) && 0 === count($restful_list) ) {
			throw new \Jolt\Exception('router_lists_empty');
		}
		
		$this->parseUri();
	}
	
	
	private function reset() {
		$this->named_list = array();
		$this->restful_list = array();
	}
	
	private function parseUri() {
		$uri = er(self::URI_PARAM, $REQUEST);
	
		$match_route = function(&$v, $k) {
			echo $uri . PHP_EOL;
		};
		
		/* Check the named list first for a matching route. */
		$named_list = $this->getNamedRouteList();
		if ( count($named_list) > 0 ) {
			array_walk($named_list, $match_route);
		}

	}
}