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
	private $fileName = NULL;
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
		
		// See if we need to append the .phtml to the end of the view name
		if ( 0 === preg_match('/\\' . self::EXT . '$/i', $view) ) {
			$view .= self::EXT;
		}
		
		// Find the view file
		//$viewFile = $this->c->
	}
	
	public function attachConfiguration(\stdClass $configuration) {
		$this->configuration = clone $configuration;
		return $this;
	}
	
	public function getVariables() {
		return $this->variables;
	}
}