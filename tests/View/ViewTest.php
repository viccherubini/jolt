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
	 * @dataProvider providerReplacementList
	 */
	public function test_View_Magic_Getter_And_Setter($k, $v) {
		$view = new Jolt_View();
		$view->$k = $v;
		
		$this->assertEquals(array($k => $v), $view->getReplacementList());
	}
	
	/**
	 * @dataProvider providerRenderableViewList
	 */
	public function test_View_Renders_When_View_File_Exists($view_name, $replacement_list) {
		$view = new Jolt_View();
		
		$app_path = rtrim(TEST_DIRECTORY, DIRECTORY_SEPARATOR) . DIRECTORY_SEPARATOR . APPLICATION_DIRECTORY;
		
		$view->setApplicationPath($app_path)->setReplacementList($replacement_list);
		$view->render($view_name);
		
		/* Cheap way to get the rendered view file. */
		$view_file = $view->getViewFile();
		$view_file_rendered = str_replace(Jolt_View::VIEW_EXT, '-rendered' . Jolt_View::VIEW_EXT, $view_file);
		
		$this->assertEquals(file_get_contents($view_file_rendered), $view->getRendering());
	}
	
	
	
	
	public function providerReplacementList() {
		return array(
			array('variable', 'value'),
			array('array_variable', array(1, 2, 3, 4, 5)),
			array('object_variable', new stdClass())
		);
	}
	
	public function providerRenderableViewList() {
		$human = new stdClass();
		$human->name = 'Victor Cherubini';
		$human->age = 25;
		
		return array(
			array('welcome', array()),
			array('replace-one', array('name' => 'Victor Cherubini')),
			array('replace-two', array('name' => 'Victor Cherubini', 'age' => 25)),
			array('list', array('list' => array('one', 'two', 'three'))),
			array('object', array('human' => $human))
		);
	}
	
}