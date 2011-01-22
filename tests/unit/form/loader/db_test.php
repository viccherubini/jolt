<?php

declare(encoding='UTF-8');
namespace jolt_test\form\loader;

use \jolt\form\loader\db,
	\jolt_test\testcase;

require_once('jolt/form/loader.php');
require_once('jolt/form/loader/db.php');

class db_test extends testcase {

	private $pdo = NULL;
	private $id = NULL;

	public function setUp() {
		$sql = file_get_contents(DIRECTORY_DB . DS . 'sqlite.sql');

		$this->pdo = $this->getMockForAbstractClass('\PDO', array('sqlite::memory:'));
		$execed = $this->pdo->exec($sql);

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
		$this->assertGreaterThan(0, count($data));
	}

	public function testLoad_ErrorsAreFound() {
		$db = $this->buildDbLoaderWithPdoAttached();
		$db->setTable('form_data');

		$id = 'form' . mt_rand(1, 20);
		$db->setId($id);

		$loaded = $db->load();
		$errors = $db->getErrors();

		$this->assertTrue($loaded);
		$this->assertTrue(is_array($errors));
		$this->assertGreaterThan(0, count($errors));
	}

	public function testLoad_StatusIsDisabled() {
		$db = $this->buildDbLoaderWithPdoAttached();
		$db->setTable('form_data');
		$db->setId('form1');

		$db->load();

		$data1 = $db->getData();
		$this->assertTrue(is_array($data1));

		// Find the data again and ensure it's status is 0
		$stmt = $this->pdo->query("SELECT * FROM form_data WHERE id = 'form1'");
		$data2 = $stmt->fetchObject();

		$this->assertEquals(0, $data2->status);
	}

	public function testLoad_CanNotReloadSameForm() {
		$db = $this->buildDbLoaderWithPdoAttached();
		$db->setTable('form_data');
		$db->setId('form1');

		$loaded1 = $db->load();
		$data1 = $db->getData();
		$this->assertTrue($loaded1);
		$this->assertTrue(is_array($data1));

		$loaded2 = $db->load();
		$data2 = $db->getData();
		$this->assertFalse($loaded2);

		$this->assertEquals($data1, $data2);
	}

	public function testLoad_AlwaysBuildsDataArray() {
		$this->pdo->query("UPDATE form_data SET data = 'not_an_array' WHERE id = 'form1'");

		$db = $this->buildDbLoaderWithPdoAttached();
		$db->setTable('form_data');
		$db->setId('form1');

		$db->load();
		$data = $db->getData();

		$this->assertTrue(is_array($data));
		$this->assertGreaterThan(0, count($data));
	}

	public function testLoad_AlwaysBuildsErrorArray() {
		$this->pdo->query("UPDATE form_data SET errors = 'not_an_array' WHERE id = 'form1'");

		$db = $this->buildDbLoaderWithPdoAttached();
		$db->setTable('form_data');
		$db->setId('form1');

		$db->load();
		$errors = $db->getErrors();

		$this->assertTrue(is_array($errors));
		$this->assertGreaterThan(0, count($errors));
	}

	private function buildDbLoaderWithPdoAttached() {
		$db = new Db;
		$db->attachPdo($this->pdo);
		$db->setName('article');

		return $db;
	}

}
