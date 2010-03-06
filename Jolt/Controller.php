<?php

declare(encoding='UTF-8');
namespace Jolt;

abstract class Controller {
	
	private $config = array();
	
	public function __construct() {
		
	}
	
	public function __destruct() {
		
	}
	
	public function setConfig(array $cfg) {
		$this->config = $cfg;
		return $this;
	}
	
	public function getConfig() {
		return $this->config;
	}
}