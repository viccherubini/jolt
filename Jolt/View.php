<?php

declare(encoding='UTF-8');
namespace Jolt;

/**
 * The basic class for rendering Views and inserting blocks/widgets into a view.
 * 
 * @author vmc <vmc@leftnode.com>
 */
class View {
	
	private $configuration = NULL;
	private $path = NULL;
	private $renderedView = NULL;
	
	private $variables = array();
	
	const EXT = '.phtml';
	
	public function __construct() {
		
	}
	
	public function __destruct() {
		
	}
	
	public function __set($k, $v) {
		$this->variables[$k] = $v;
		return $this;
	}
	
	public function __get($k) {
		if ( isset($this->variables[$k]) ) {
			return $this->variables[$k];
		}
		return NULL;
	}
	
	public function render($view) {
		if ( is_null($this->configuration) ) {
			throw new \Jolt\Exception('view_configuration_is_empty');
		}
		
		$path = $this->configuration->viewPath;
		
		// See if we need to append the .phtml to the end of the view name
		if ( 0 === preg_match('/\\' . self::EXT . '$/i', $view) ) {
			$view .= self::EXT;
		}
		
		$pathLength = strlen($path);
		if ( $path[$pathLength-1] != DIRECTORY_SEPARATOR ) {
			$path .= DIRECTORY_SEPARATOR;
		}
		
		// Find the view file
		$viewPath = $path . $view;
		if ( !is_file($viewPath) ) {
			throw new \Jolt\Exception('view_path_not_found');
		}
		
		extract($this->variables);
		ob_start();
			require $viewPath;
		$this->renderedView = ob_get_clean();
		
		return true;
	}
	
	public function attachConfiguration(\Jolt\Configuration $configuration) {
		$this->configuration = clone $configuration;
		return $this;
	}

	public function getRenderedView() {
		return $this->renderedView;
	}
	
	public function getVariables() {
		return $this->variables;
	}
}