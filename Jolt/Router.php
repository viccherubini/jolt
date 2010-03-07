<?php

declare(encoding='UTF-8');
namespace Jolt;

require_once 'Exception.php';

class Router {

	private $config = array();
	private $named_list = array();
	private $restful_list = array();

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
			if ( false === $v instanceof \Jolt\Route ) {
				throw new \Jolt\Exception('router_named_list_element_not_route');
			}
		});
		
		$this->named_list = $named_list;
		return $this;
	}
	
	public function setRestfulRouteList(array $restful_list) {
		
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
		
		
	}
	
	
	private function reset() {
		$this->named_list = array();
		$this->restful_list = array();
	}
}