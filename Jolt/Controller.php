<?php

abstract class Jolt_Controller {
	
	private $layout = NULL;
	
	private $layout_object = NULL;
	
	private $config = array();
	
	public function __construct() {
		
	}
	
	public function __destruct() {
		
	}

	public function render($view, $block=NULL) {
		
		
	}
	
	
	

	public function getConfig() {
		return $this->config;
	}
	
	public function getLayout() {
		return $this->layout;
	}
	
	
	
	
	public function setConfig(array $config) {
		$this->config = $config;
		return $this;
	}
	
	
	public function setLayout($layout) {
		$this->layout = $layout;
		return $this;
	}
	
	
	
}