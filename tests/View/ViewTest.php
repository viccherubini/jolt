<?php

require_once 'Jolt/View.php';

class Jolt_View_ViewTest extends Jolt_TestCase {
	
	public function test_View_Application_Path_Is_Set() {
		$view = new Jolt_View();
		$view->setApplicationPath(getcwd());
		
		$this->assertEquals(getcwd(), $view->getApplicationPath());
	}
	
	public function test_View_Block_Directory_Is_Set() {
		$view = new Jolt_View();
		$view->setBlockDirectory('blocks/'); // The / should be trimmed off
		
		$this->assertEquals('blocks', $view->getBlockDirectory());
	}
	
	
	
}