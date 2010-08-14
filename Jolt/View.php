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
	private $cssPath = NULL;
	private $jsPath = NULL;
	private $imagePath = NULL;
	private $secureUrl = NULL;
	private $url = NULL;
	private $useRewrite = false;
	private $viewPath = NULL;
	
	private $renderedView = NULL;
	private $variables = array();
	private $blockList = array();
	
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
	
	public function attachConfiguration(\Jolt\Configuration $cfg) {
		$this->cfg = clone $cfg;
		return $this;
	}
	
	public function addBlock($blockName, $block) {
		$this->blockList[$blockName] = $block;
		return $this;
	}
	
	public function render($view) {
		$view = $this->appendExtension($view, self::EXT);
		
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

	public function safe($v) {
		return htmlentities($v, ENT_COMPAT, 'UTF-8');
	}

	public function css($cssFile, $media='screen', $localFile=true) {
		$cssFile = $this->appendExtension($cssFile, '.css');
		
		if ( $localFile ) {
			$cssFile = DIRECTORY_SEPARATOR . $this->cssPath . $cssFile;
		}
		
		$linkTag = sprintf('<link type="text/css" rel="stylesheet" href="%s" media="%s">%s', $cssFile, $media, PHP_EOL);
		
		return $linkTag;
	}
	
	public function href($url, $text, $tagAttributes=NULL, $localUrl=true, $secure=false) {
		if ( $localUrl ) {
			if ( $secure ) {
				$url = $this->urls($url);
			} else {
				$url = $this->url($url);
			}
		}
		
		$text = $this->safe($text);
		$href = sprintf('<a href="%s" %s>%s</a>%s', $url, $tagAttributes, $text, PHP_EOL);
		
		return $href;
	}
	
	public function hrefs($url, $text, $tagAttributes=NULL, $localUrl=true) {
		return $this->href($url, $text, $tagAttributes, $localUrl, true);
	}
	
	public function img($imgSrc, $altText=NULL, $tagAttributes=NULL, $localFile=true) {
		if ( $localFile ) {
			$imgSrc = DIRECTORY_SEPARATOR . $this->imagePath . $imgSrc;
		}
		
		$imgTag = sprintf('<img src="%s" alt="%s" title="%s" %s">%s', $imgSrc, $altText, $altText, $tagAttributes, PHP_EOL);
		
		return $imgTag;
	}
	
	public function js($jsFile, $localFile=true) {
		$jsFile = $this->appendExtension($jsFile, '.js');
		
		if ( $localFile ) {
			$jsFile = DIRECTORY_SEPARATOR . $this->jsPath . $jsFile;
		}
		
		$jsTag = sprintf('<script src="%s" type="text/javascript"></script>%s', $jsFile, PHP_EOL);
		
		return $jsTag;
	}
	
	public function url() {
		$argc = func_num_args();
		$argv = func_get_args();
		
		$p = $this->makeUrlParameters($argc, $argv);
		$url = $this->url . $p;
		
		return $url;
	}
	
	public function urls() {
		$argc = func_num_args();
		$argv = func_get_args();
		
		$p = $this->makeUrlParameters($argc, $argv);
		$url = $this->secureUrl . $p;
		
		return $url;
	}

	public function setCssPath($cssPath) {
		$this->cssPath = $this->appendDirectorySeparator($cssPath);
		return $this;
	}

	public function setImagePath($imagePath) {
		$this->imagePath = $this->appendDirectorySeparator($imagePath);
		return $this;
	}

	public function setJsPath($jsPath) {
		$this->jsPath = $this->appendDirectorySeparator($jsPath);
		return $this;
	}

	public function setRouteParameter($routeParameter) {
		$this->routeParameter = trim($routeParameter);
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
		$this->viewPath = $this->appendDirectorySeparator($viewPath);
		return $this;
	}

	public function getBlockList() {
		return $this->blockList;
	}
	
	public function getBlock($blockName) {
		if ( isset($this->blockList[$blockName]) ) {
			return $this->blockList[$blockName];
		}
		return NULL;
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

	private function appendExtension($file, $ext) {
		if ( 0 == preg_match("/\\{$ext}$/i", $file) ) {
			$file .= $ext;
		}
		return $file;
	}
	
	private function appendDirectorySeparator($path) {
		$pathLength = strlen(trim($path)) - 1;
		if ( $path[$pathLength] != DIRECTORY_SEPARATOR ) {
			$path .= DIRECTORY_SEPARATOR;
		}
		return $path;
	}

	private function makeUrlParameters($argc, $argv) {
		if ( 0 == $argc ) {
			return NULL;
		}
		
		$route = NULL;
		$root = $argv[0];
		if ( $argc > 1 ) {
			$route = '/' . implode('/', array_slice($argv, 1));
		}
		
		$parameters = $root . $route;
		if ( !$this->useRewrite ) {
			$parameters = "index.php?{$this->routeParameter}={$parameters}";
		}
		
		return $parameters;
	}
}