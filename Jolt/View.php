<?php

/**
 * The basic class for rendering views.
 * 
 * @author vmc <vmc@leftnode.com>
 */
class Jolt_View {
	
	const VIEW_DIR = 'view';
	const VIEW_EXT = '.phtml';
	
	private $replacement_list = array();
	
	
	private $application_path = NULL; 
	
	private $block_directory = NULL;
	
	private $rendering = NULL;
	
	private $view_file = NULL;
	
	public function __construct() {
		
	}
	
	public function __destruct() {
		
		
	}
	
	public function __get($k) {
		return er($k, $this->variable_list, NULL);
	}
	
	public function __set($k, $v) {
		$this->replacement_list[$k] = $v;
		return true;
	}
	
	
	public function insertBlock($block_name) {
		
	}
	
	public function render($view) {
		/**
		 * 1. Determine the path to the view file.
		 * 2. Get all of the variables to replace.
		 * 3. Require the file, and ob() it.
		 * 4. Any calls to insertBlock() are processed.
		 */
		$application_path = $this->getApplicationPath();
		
		$ds = DIRECTORY_SEPARATOR;
		$view_file = implode($ds, array($application_path, self::VIEW_DIR, $view)) . self::VIEW_EXT;
		
		$rendering = NULL;
		
		if ( true === is_file($view_file) ) {
			$this->setViewFile($view_file);
			extract($this->getReplacementList());
			ob_start();
			require $view_file;
			$rendering = ob_get_clean();
		}
		
		$this->setRendering($rendering);
		
		return $this;
	}
	
	
	public function getApplicationPath() {
		return $this->application_path;
	}
	
	public function getBlockDirectory() {
		return $this->block_directory;
	}
	
	public function getRendering() {
		return $this->rendering;
	}
	
	public function getReplacementList() {
		return $this->replacement_list;
	}
	
	public function getViewFile() {
		return $this->view_file;
	}
	
	
	public function setApplicationPath($application_path) {
		$this->application_path = rtrim($application_path, DIRECTORY_SEPARATOR);
		return $this;
	}
	
	public function setBlockDirectory($block_directory) {
		$this->block_directory = rtrim($block_directory, DIRECTORY_SEPARATOR);
		return $this;
	}
	
	public function setRendering($rendering) {
		$this->rendering = $rendering;
		return $this;
	}
	
	public function setReplacementList(array $replacement_list) {
		$this->replacement_list = (array)$replacement_list;
		return $this;
	}
	
	public function setViewFile($view_file) {
		$this->view_file = $view_file;
		return $this;
	}
	
}