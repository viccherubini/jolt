<?php

declare(encoding='UTF-8');
namespace JoltTest\Form\Writer;

use \Jolt\Form\Writer\Db,
	\JoltTest\TestCase;

require_once 'Jolt/Form/Writer.php';
require_once 'Jolt/Form/Writer/Db.php';

class DbTest extends TestCase {

	/**
	 * @expectedException PHPUnit_Framework_Error
	 */
	public function testAttachPdo_MustBePdoObject() {
		$db = new Db;
		$db->attachPdo(NULL);
	}

	public function testAttachPdo_SilentErrors() {
		$pdo = $this->getMockForAbstractClass('\PDO', array('sqlite:memory:'));

		$db = new Db;
		$db->attachPdo($pdo);

		$pdo = $db->getPdo();
	}

}