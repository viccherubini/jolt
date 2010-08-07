<?php

declare(encoding='UTF-8');
namespace Jolt;

/**
 * The basic class for rendering Views and inserting blocks/widgets into a view.
 * 
 * @author vmc <vmc@leftnode.com>
 */
class View {
	
	// Configuration
	private $secureUrl = NULL;
	private $url = NULL;
	private $useRewrite = false;
	private $viewPath = NULL;
	
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
		if ( 0 === preg_match('/\\' . self::EXT . '$/i', $view) ) {
			$view .= self::EXT;
		}
		
		// Find the view file
		$viewFile = $this->viewPath . $view;
		if ( !is_file($viewFile) ) {
			throw new \Jolt\Exception('view_path_not_found');
		}
		
		extract($this->variables);
		ob_start();
			require $viewFile;
		$this->renderedView = ob_get_clean();
		
		return $this;
	}

	public function setSecureUrl($secureUrl) {
		$this->secureUrl = trim($secureUrl);
		return $this;
	}
	
	public function setUrl($url) {
		$this->url = trim($url);
		return $this;
	}
	
	public function setUseRewrite($useRewrite) {
		if ( !is_bool($useRewrite) ) {
			$useRewrite = false;
		}
		
		$this->useRewrite = $useRewrite;
		return $this;
	}
	
	public function setViewPath($viewPath) {
		$viewPathLength = strlen(trim($viewPath));
		if ( $viewPath[$viewPathLength-1] != DIRECTORY_SEPARATOR ) {
			$viewPath .= DIRECTORY_SEPARATOR;
		}
		
		$this->viewPath = trim($viewPath);
		return $this;
	}

	public function getRenderedView() {
		return $this->renderedView;
	}
	
	public function getSecureUrl() {
		return $this->secureUrl;
	}
	
	public function getUrl() {
		return $this->url;
	}
	
	public function getUseRewrite() {
		return $this->useRewrite;
	}
	
	public function getViewPath() {
		return $this->viewPath;
	}
	
	public function getVariables() {
		return $this->variables;
	}
}