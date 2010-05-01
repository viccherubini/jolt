<?php

abstract class Jolt_Controller {
	
	private $config = array();
	
	public function __construct() {
		
	}
	
	public function __destruct() {
		
	}
	
	public function setConfig(array $config) {
		$this->config = $config;
		return $this;
	}
	
	public function getConfig() {
		return $this->config;
	}
}