<?php

declare(encoding='UTF-8');
namespace jolt_test\form\writer;

use \jolt\form\writer\db,
	\jolt_test\testcase;

require_once('jolt/form/writer.php');
require_once('jolt/form/writer/db.php');

class db_test extends testcase {

	private $pdo = NULL;

	private $formData = array();

	public function setUp() {
		$sql = file_get_contents(DIRECTORY_DB . DS . 'sqlite.sql');

		$this->pdo = $this->getMockForAbstractClass('\PDO', array('sqlite::memory:'));
		$this->pdo->exec($sql);

		$this->formData = array(
			'title' => 'The title of the "article" goes here',
			'content' => "Article's are just about the 'coolest!' thing you can wrote content is here",
			'created' => date('Y-m-d H:i:s', time())
		);

		$this->errors = array(
			'title' => 'The field Title can not be empty.',
			'content' => 'The field Content must be larger than 20 characters.'
		);
	}

	/**
	 * @expectedException PHPUnit_Framework_Error
	 */
	public function testAttachPdo_MustBePdoObject() {
		$db = new Db;
		$db->attachPdo(NULL);
	}

	public function testWrite_FailsForNoPdo() {
		$db = new Db;

		$written = $db->write();
		$this->assertFalse($written);
	}

	public function testWrite_FailsForNoData() {
		$db = new Db;
		$db->attachPdo($this->pdo);

		$written = $db->write();
		$this->assertFalse($written);
	}

	public function testWrite_FailsForNoId() {
		$db = new Db;
		$db->attachPdo($this->pdo);
		$db->setData(array('name' => 'Vic Cherubini', 'age' => 26));

		$written = $db->write();
		$this->assertFalse($written);
	}

	public function testWrite_FailsForMissingTable() {
		$db = $this->buildDbWriterWithPdoAttached('article');
		$db->setTable('missing_table');

		$written = $db->write();
		$this->assertFalse($written);
	}

	public function testWrite_WritesFormData() {
		$db = $this->buildDbWriterWithPdoAttached('article');

		$written = $db->write();
		$this->assertTrue($written);
	}

	public function testWrite_WritesToDifferentTable() {
		$db = $this->buildDbWriterWithPdoAttached('article');
		$db->setTable('form_alternative');

		$written = $db->write();
		$this->assertTrue($written);
	}




	private function buildDbWriterWithPdoAttached($name) {
		$db = new Db;

		$db->attachPdo($this->pdo);
		$db->setId(uniqid('jolt.', true));
		$db->setName($name);
		$db->setData($this->formData);
		$db->setErrors($this->errors);

		return $db;
	}
}
