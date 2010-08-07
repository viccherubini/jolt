<?php

declare(encoding='UTF-8');
namespace Jolt;

/**
 * Base class for building a Controller object to manipulate the views. Once a Route is resolved, a Controller::action()
 * is executed.
 */
abstract class Controller {
	
	private $blockList = array();
	private $renderedView = NULL;
	private $action = NULL;
	private $view = NULL;
	
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
		
		return $this;
	}
	
	public function setAction($action) {
		$this->action = $action;
		return $this;
	}
	
	
}