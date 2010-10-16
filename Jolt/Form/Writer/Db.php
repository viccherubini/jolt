<?php

declare(encoding='UTF-8');
namespace Jolt\Form\Writer;
use \Jolt\Form\Writer;

class Db extends Writer {

	private $pdo = NULL;

	public function attachPdo(\PDO $pdo) {
		$pdo->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_SILENT);
		$pdo->setAttribute(\PDO::ATTR_EMULATE_PREPARES, false);
		$this->pdo = $pdo;

		return $this;
	}

	public function write() {

	}


	public function getPdo() {
		return $this->pdo;
	}
}