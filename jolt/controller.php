<?php

declare(encoding='UTF-8');
namespace jolt;

class controller {

	private $action = NULL;
	private $content_type = 'text/html';
	private $renderedController = NULL;
	private $renderedView = NULL;
	private $responseCode = 200;
	private $view = NULL;

	private $headers = array();
	private $blockList = array();

	const EXT = '.php';
	const VIEWEXT = '.phtml';

	public function __construct() {

	}

	public function __destruct() {

	}

	public function __set($k, $v) {
		if ($this->hasView()) {
			$this->view->$k = $v;
		}
		return true;
	}

	public function __get($k) {
		if ($this->hasView()) {
			return $this->view->$k;
		}
		return NULL;
	}

	public function addBlock($blockName, $block) {
		if ($this->hasView()) {
			$this->view->addBlock($blockName, $block);
		}
		return $this;
	}

	public function add_header($header, $value) {
		if ('content-type' === strtolower($header)) {
			$this->content_type = $value;
		} else {
			$this->headers[$header] = $value;
		}
		return $this;
	}

	public function attach_view(\Jolt\View $view) {
		$this->view = $view;
		return $this;
	}

	public function execute($argv) {
		if ( !is_array($argv) ) {
			$argv = array($argv);
		}
		$argc = count($argv);

		if ( empty($this->action) ) {
			throw new \Jolt\Exception('controller_action_not_set');
		}

		try {
			$action = new \ReflectionMethod($this, $this->action);
		} catch ( \ReflectionException $e ) {
			throw new \Jolt\Exception('controller_action_not_part_of_class');
		}

		$paramCount = $action->getNumberOfRequiredParameters();
		if ( $paramCount != $argc && $paramCount > $argc ) {
			$argv = array_pad($argv, $paramCount, NULL);
		}

		ob_start();
			if ( method_exists($this, 'init') ) {
				$this->init();
			}

			if ( $action->isPublic() ) {
				if ( $action->isStatic() ) {
					$action->invokeArgs(NULL, $argv);
				} else {
					$action->invokeArgs($this, $argv);
				}
			}
		$renderedController = ob_get_clean();

		if ( !empty($renderedController) ) {
			$this->renderedController = $renderedController;
		} else {
			$this->renderedController = $this->renderedView;
		}

		return $this->renderedController;
	}

	public function render($viewName=NULL) {
		if ( empty($viewName) ) {
			$viewName = $this->action;
		}

		$this->renderedView = $this->renderView($viewName);
		return $this->renderedView;
	}

	public function renderToBlock($viewName, $blockName) {
		$renderedView = $this->renderView($viewName);
		$this->addBlock($blockName, $renderedView);

		return $this;
	}

	public function renderView($viewName) {
		$view = $this->checkView();

		$renderedView = $view->render($viewName)
			->getRenderedView();

		return $renderedView;
	}

	public function setAction($action) {
		$this->action = trim($action);
		return $this;
	}

	public function setcontent_type($content_type) {
		$this->content_type = trim($content_type);
		return $this;
	}

	public function setResponseCode($responseCode) {
		$this->responseCode = intval($responseCode);
		return $this;
	}

	public function getAction() {
		return $this->action;
	}

	public function getBlockList() {
		if ( $this->hasView() ) {
			return $this->view->getBlockList();
		}
		return array();
	}

	public function getBlock($blockName) {
		if ( $this->hasView() ) {
			return $this->view->getBlock($blockName);
		}
		return NULL;
	}

	public function get_content_type() {
		return $this->content_type;
	}

	public function get_headers() {
		return $this->headers;
	}

	public function get_header($header) {
		if (isset($this->headers[$header])) {
			return $this->headers[$header];
		}
		return NULL;
	}

	public function getRenderedController() {
		return $this->renderedController;
	}

	public function getRenderedView() {
		return $this->renderedView;
	}

	public function getResponseCode() {
		return $this->responseCode;
	}

	public function getView() {
		return $this->view;
	}

	private function checkView() {
		if ( !$this->hasView() ) {
			throw new \Jolt\Exception('controller_view_not_jolt_view');
		}
		return $this->view;
	}

	private function hasView() {
		return ( $this->view instanceof \Jolt\View );
	}

}