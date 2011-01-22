<?php

declare(encoding='UTF-8');
namespace jolt_test;

use \jolt_test\testcase;

class misc_test extends testcase {

	const EXT = '.phtml';

	/**
	 * @dataProvider providerViewAndViewFile
	 */
	public function testPregMatch_AppendsExtension($view, $expected) {
		if ( 0 === preg_match('/\\' . self::EXT . '$/i', $view) ) {
			$view .= self::EXT;
		}

		$this->assertEquals($expected, $view);
	}

	/**
	 * @expectedException PHPUnit_Framework_Error
	 */
	public function testStdClass_CanNotAccessMissingField() {
		$c = new \stdClass;

		var_dump($c->field);
	}


	public function providerViewAndViewFile() {
		return array(
			array('view', 'view' . self::EXT),
			array('view.php', 'view.php' . self::EXT),
			array('view.PHTML', 'view' . strtoupper(self::EXT)),
			array('view.name.here', 'view.name.here' . self::EXT),
			array('view/in/subdirectories', 'view/in/subdirectories' . self::EXT),
			array('view.phtml', 'view' . self::EXT),
			array('.phtml', self::EXT),
			array('.phtml.phtml', '.phtml' . self::EXT),
			array('.phtml.view', '.phtml.view' . self::EXT)
		);
	}
}