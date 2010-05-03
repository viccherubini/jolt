<?php

/**
 * The basic class for rendering views.
 * 
 * @author vmc <vmc@leftnode.com>
 */
class Jolt_View {
	
	const EXT = '.phtml';
	
	private $variable_list = array();
	
	
	private $application_path = NULL; 
	
	private $block_directory = NULL;
	
	public function __construct() {
		
	}
	
	public function __destruct() {
		
		
	}
	
	public function __get($k) {
		return er($k, $this->variable_list, NULL);
	}
	
	public function __set($k, $v) {
		$this->variable_list[$k] = $v;
		return true;
	}
	
	
	public function insertBlock($block_name) {
		
	}
	
	public function render($controller, $view) {
		/**
		 * 1. Determine the path to the view file.
		 * 2. Get all of the variables to replace.
		 * 3. Require the file, and ob() it.
		 * 4. Any calls to insertBlock() are processed.
		 */
		
	}
	
	
	public function getApplicationPath() {
		return $this->application_path;
	}
	
	public function getBlockDirectory() {
		return $this->block_directory;
	}
	
	public function getVariableList() {
		return $this->variable_list;
	}
	
	
	public function setApplicationPath($application_path) {
		$this->application_path = rtrim($application_path, DIRECTORY_SEPARATOR);
		return $this;
	}
	
	public function setBlockDirectory($block_directory) {
		$this->block_directory = rtrim($block_directory, DIRECTORY_SEPARATOR);
		return $this;
	}
	
}