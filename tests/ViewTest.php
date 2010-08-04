<?php

declare(encoding='UTF-8');
namespace JoltTest;

use \Jolt\View,
	\JoltTest\TestCase;

require_once 'Jolt/View.php';

class ViewTest extends TestCase {
	
	private $c = NULL;
	
	public function setUp() {
		$this->c = $this->buildMockConfiguration(array(
			'viewPath' => DIRECTORY_VIEWS,
			'url' => 'http://joltcore.dev',
			'secureUrl' => 'https://joltcore.dev',
			'useRewrite' => true
			)
		);
	}
	
	public function test__Set_WritesToVariables() {
		$expected = array('name' => 'vic');
		
		$view = new View;
		$view->name = 'vic';
	
		$this->assertEquals($expected, $view->getVariables());
	}
	
	public function test__Get_RetrievesFromVariables() {
		$name = 'vic';
		
		$view = new View;
		$view->name = $name;
		
		$this->assertEquals($name, $view->name);
	}
	
	public function test__Get_ReturnsNullWhenVariableNotFound() {
		$view = new View;
		
		$this->assertTrue(is_null($view->name));
	}

	/**
	 * @expectedException \Jolt\Exception
	 */
	public function testRender_ConfigurationMustBeSet() {
		$view = new View;
		
		$view->render('view');
	}
	
	/**
	 * @expectedException \Jolt\Exception
	 */
	public function testRender_ViewFileMustExist() {
		$view = new View;
		$view->attachConfiguration($this->c);
	
		$view->render('missing-view');
	}
	
	/**
	 * @dataProvider providerViewWithVariables
	 */
	public function testRender_ViewRenders($viewName, $variables) {
		$view = new View;
		$view->attachConfiguration($this->c);
	
		foreach ( $variables as $k => $v ) {
			$view->$k = $v;
		}
		
		$view->render($viewName);
		
		$this->assertEquals($this->loadRenderedView($viewName), $view->getRenderedView());
	}
	
	public function providerViewWithVariables() {
		$name = 'Victor Cherubini';
		$age = 25;
		
		$human = new \stdClass;
		$human->name = $name;
		$human->age = $age;
		
		return array(
			array('list', array('list' => array('one', 'two', 'three'))),
			array('object', array('human' => $human)),
			array('replace-one', array('name' => $name)),
			array('replace-two', array('name' => $name, 'age' => $age)),
			array('welcome', array())
		);
	}
	
	private function loadRenderedView($view) {
		$path = DIRECTORY_VIEWS . DIRECTORY_SEPARATOR . $view . '-rendered' . View::EXT;
		if ( !is_file($path) ) {
			return NULL;
		}
		
		$renderedView = file_get_contents($path);
		return $renderedView;
	}
}