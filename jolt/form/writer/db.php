<?php

declare(encoding='UTF-8');
namespace Jolt\Form\Writer;
use \Jolt\Form\Writer;

require_once 'jolt/form/writer.php';

class Db extends Writer {

	private $pdo = NULL;
	private $table = 'form';

	public function attachPdo(\PDO $pdo) {
		$pdo->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_SILENT);
		$pdo->setAttribute(\PDO::ATTR_EMULATE_PREPARES, false);
		$this->pdo = $pdo;

		return $this;
	}

	public function write() {
		$pdo = $this->getPdo();
		if ( is_null($pdo) ) {
			return false;
		}

		$data = $this->getData();
		if ( empty($data) || 0 === count($data) ) {
			return false;
		}

		$id = $this->getId();
		if ( empty($id) ) {
			return false;
		}

		$created = date('Y-m-d H:i:s', time());
		$name = $this->getName();
		$errors = $this->getErrors();
		$table = $this->getTable();

		$dataJson = json_encode($data);
		$errorsJson = json_encode($errors);

		$sql = "INSERT INTO {$table}
				(created, id, name, data, errors, status)
			VALUES
				(:created, :id, :name, :data, :errors, :status)";

		$pdo->beginTransaction();
			$stmt = $pdo->prepare($sql);

			if ( !$stmt ) {
				$pdo->rollback();
				return false;
			}

			$parameters = array(
				'created' => $created,
				'id' => $id,
				'name' => $name,
				'data' => $dataJson,
				'errors' => $errorsJson,
				'status' => 1
			);

			$executed = $stmt->execute($parameters);
		$pdo->commit();

		return $executed;
	}

	public function setTable($table) {
		$this->table = trim($table);
		return $this;
	}

	public function getPdo() {
		return $this->pdo;
	}

	public function getTable() {
		return $this->table;
	}

}