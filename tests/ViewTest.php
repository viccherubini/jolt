<?php

declare(encoding='UTF-8');
namespace JoltTest;

use \Jolt\View,
	\JoltTest\TestCase;

require_once 'Jolt/View.php';

class ViewTest extends TestCase {
	
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
	
		$view->render('missing-view');
	}
	
	/**
	 * @dataProvider providerViewWithVariables
	 */
	public function testRender_ViewRenders($viewName, $variables) {
		$view = new View;
		$view->setViewPath(DIRECTORY_VIEWS);
	
		foreach ( $variables as $k => $v ) {
			$view->$k = $v;
		}
		
		$view->render($viewName);
		
		$this->assertEquals($this->loadRenderedView($viewName), $view->getRenderedView());
	}
	
	public function testSetSecureUrl_IsTrimmed() {
		$secureUrl = " https://joltcore.org/ \t   ";
		$secureUrlTrimmed = "https://joltcore.org/";
		
		$view = new View;
		$view->setSecureUrl($secureUrl);
		
		$this->assertEquals($secureUrlTrimmed, $view->getSecureUrl());
	}
	
	public function testUrl_IsTrimmed() {
		$url = " http://joltcore.org/ \t   ";
		$urlTrimmed = "http://joltcore.org/";
		
		$view = new View;
		$view->setUrl($url);
		
		$this->assertEquals($urlTrimmed, $view->getUrl());
	}
	
	public function testSetUseRewrite_IsFalse() {
		$useRewriteString = 'string';
		
		$view = new View;
		$view->setUseRewrite($useRewriteString);
		
		$this->assertFalse($view->getUseRewrite());
	}
	
	public function testSetUseRewrite_IsBoolean() {
		$view = new View;
		
		$view->setUseRewrite(true);
		$this->assertTrue($view->getUseRewrite());
		
		$view->setUseRewrite(false);
		$this->assertFalse($view->getUseRewrite());
	}
	
	public function testSetViewPath_AppendsSeparator() {
		$view = new View;
		$view->setViewPath(DIRECTORY_VIEWS);
		
		$this->assertEquals(DIRECTORY_VIEWS . DIRECTORY_SEPARATOR, $view->getViewPath());
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
		$path = DIRECTORY_VIEWS . DS . $view . '-rendered' . View::EXT;
		if ( !is_file($path) ) {
			return NULL;
		}
		
		$renderedView = file_get_contents($path);
		return $renderedView;
	}
}