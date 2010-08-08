<?php

declare(encoding='UTF-8');
namespace Jolt;

/**
 * Base class for building a Controller object to manipulate the views. Once a Route is resolved, a Controller::action()
 * is executed.
 */
abstract class Controller {
	
	private $blockList = array();
	private $renderedController = NULL;
	private $renderedView = NULL;
	private $action = NULL;
	private $view = NULL;
	
	const EXT = '.php';
	const VIEWEXT = '.phtml';
	
	public function __construct() {
		
	}
	
	public function __destruct() {
		
	}
	
	public function __set($k, $v) {
		if ( $this->view instanceof \Jolt\View ) {
			$this->view->$k = $v;
		}
		return true;
	}
	
	public function __get($k) {
		if ( $this->view instanceof \Jolt\View ) {
			return $this->view->$k;
		}
		return NULL;
	}
	
	public function addBlock($blockName, $block) {
		$this->blockList[$blockName] = $block;
		return $this;
	}
	
	public function attachView(\Jolt\View $view) {
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
	
	public function render($viewName=NULL, $blockName=NULL) {
		if ( !($this->view instanceof \Jolt\View) ) {
			throw new \Jolt\Exception('controller_view_not_jolt_view');
		}
		
		if ( empty($viewName) ) {
			$viewName = $this->action;
		}
		
		$renderedView = $this->view
			->render($viewName)
			->getRenderedView();
		
		if ( !empty($blockName) ) {
			$this->addBlock($blockName, $renderedView);
		}
		
		$this->renderedView = $renderedView;
		return $this->renderedView;
	}
	
	public function setAction($action) {
		$this->action = trim($action);
		return $this;
	}
	
	public function getAction() {
		return $this->action;
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
	
	public function getRenderedController() {
		return $this->renderedController;
	}
	
	public function getRenderedView() {
		return $this->renderedView;
	}
	
}