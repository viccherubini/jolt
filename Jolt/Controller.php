<?php

declare(encoding='UTF-8');
namespace Jolt;

abstract class Controller {
	
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
	
	public function attachView(\Jolt\View $view) {
		$this->view = $view;
		return $this;
	}
	
	public function execute($argv) {
		
	}
	
	public function render($name=NULL, $block=NULL) {
		
	}
	
	public function setAction($action) {
		$this->action = $action;
		return $this;
	}
	
	
}