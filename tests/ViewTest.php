<?php

declare(encoding='UTF-8');
namespace JoltTest;

use \Jolt\View;

require_once 'Jolt/View.php';

class ViewTest extends TestCase {
	
	public function testApplicationPathIsSetCorrectly() {
		$view = new View();
		$view->setApplicationPath(getcwd());
		
		$this->assertEquals(getcwd(), $view->getApplicationPath());
	}
	
	public function testBlockDirectoryIsSetCorrectly() {
		$view = new View();
		$view->setBlockDirectory('blocks/'); // The / should be trimmed off
		
		$this->assertEquals('blocks', $view->getBlockDirectory());
	}
	
	/**
	 * @dataProvider providerReplacementList
	 */
	public function testMagicGetterAndSetter($k, $v) {
		$view = new View();
		$view->$k = $v;
		
		$this->assertEquals(array($k => $v), $view->getReplacementList());
		$this->assertEquals($v, $view->$k);
	}
	
	/**
	 * @dataProvider providerRenderableViewList
	 */
	public function testViewRendersCorrectlyWhenViewFileExists($view_name, $replacement_list) {
		$view = new View();
		
		$app_path = rtrim(TEST_DIRECTORY, DIRECTORY_SEPARATOR) . DIRECTORY_SEPARATOR . APPLICATION_DIRECTORY;
		
		$view->setApplicationPath($app_path)->setReplacementList($replacement_list);
		$view->render($view_name);
		
		/* Cheap way to get the rendered view file. */
		$view_file = $view->getViewFile();
		$view_file_rendered = str_replace(View::VIEW_EXT, '-rendered' . View::VIEW_EXT, $view_file);
		
		$this->assertEquals(file_get_contents($view_file_rendered), $view->getRendering());
	}
	
	
	public function providerReplacementList() {
		return array(
			array('variable', 'value'),
			array('array_variable', array(1, 2, 3, 4, 5)),
			array('object_variable', new \stdClass())
		);
	}
	
	public function providerRenderableViewList() {
		$human = new \stdClass();
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