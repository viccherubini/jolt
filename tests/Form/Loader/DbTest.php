<?php

declare(encoding='UTF-8');
namespace JoltTest\Form\Loader;

use \Jolt\Form\Loader\Db,
	\JoltTest\TestCase;

require_once 'Jolt/Form/Loader.php';
require_once 'Jolt/Form/Loader/Db.php';

class DbTest extends TestCase {

	private $pdo = NULL;
	private $id = NULL;

	public function setUp() {
		$sql = file_get_contents(DIRECTORY_DB . DS . 'sqlite.sql');

		$this->pdo = $this->getMockForAbstractClass('\PDO', array('sqlite::memory:'));
		$this->pdo->exec($sql);

		$this->id = uniqid('jolt.', true);
	}

	/**
	 * @expectedException PHPUnit_Framework_Error
	 */
	public function testAttachPdo_MustBePdoObject() {
		$db = new Db;
		$db->attachPdo(NULL);
	}

	public function testLoad_FailsForNoPdo() {
		$db = new Db;

		$loaded = $db->load();
		$this->assertFalse($loaded);
	}

	public function testLoad_FailsForNoId() {
		$db = new Db;
		$db->attachPdo($this->pdo);
		$db->setName('articles');

		$loaded = $db->load();
		$this->assertFalse($loaded);
	}

	public function testLoad_FailsForNoName() {
		$db = new Db;
		$db->attachPdo($this->pdo);
		$db->setId($this->id);

		$loaded = $db->load();
		$this->assertFalse($loaded);
	}

	public function testLoad_FailsForMissingTable() {
		$db = $this->buildDbLoaderWithPdoAttached();
		$db->setId($this->id);
		$db->setTable('missing_table');

		$loaded = $db->load();

		$this->assertFalse($loaded);
	}

	public function _testLoad_UpdateFailsForMissingTable() {
		// Load the correct form, then drop the table, then attempt to update it.

	}


	public function testLoad_DataIsFound() {
		$db = $this->buildDbLoaderWithPdoAttached();
		$db->setTable('form_data');

		// There are, at most, 20 rows in the form_data table defined in the sqlite.sql file
		$id = 'form' . mt_rand(1, 20);
		$db->setId($id);

		$loaded = $db->load();
		$data = $db->getData();

		$this->assertTrue($loaded);
		$this->assertTrue(is_array($data));
	}


	private function buildDbLoaderWithPdoAttached() {
		$db = new Db;
		$db->attachPdo($this->pdo);
		$db->setName('article');

		return $db;
	}
}