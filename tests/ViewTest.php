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
			'viewDirectory' => DIRECTORY_VIEWS,
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
	
		$view->render('view');
	}
}