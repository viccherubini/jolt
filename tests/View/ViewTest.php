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
	
	/**
	 * @dataProvider providerViewVariable
	 */
	public function test_View_Magic_Getter_And_Setter($k, $v) {
		$view = new Jolt_View();
		$view->$k = $v;
		
		$value_list = array($k => $v);
		
		$this->assertEquals($value_list, $view->getVariableList());
	}
	
	
	
	
	public function providerViewVariable() {
		return array(
			array('variable', 'value'),
			array('array_variable', array(1, 2, 3, 4, 5)),
			array('object_variable', new stdClass())
		);
	}
	
}