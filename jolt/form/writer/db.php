<?php

declare(encoding='UTF-8');
namespace jolt\form\writer;
use \jolt\form\writer;

require_once('jolt/form/writer.php');

class db extends writer {

	private $pdo = NULL;
	private $table = 'form';

	public function attach_pdo(\PDO $pdo) {
		$pdo->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_SILENT);
		$pdo->setAttribute(\PDO::ATTR_EMULATE_PREPARES, false);
		$this->pdo = $pdo;

		return $this;
	}

	public function write() {
		$pdo = $this->get_pdo();
		if (is_null($pdo)) {
			return false;
		}

		$data = $this->get_data();
		if (empty($data) || 0 === count($data)) {
			return false;
		}

		$id = $this->get_id();
		if (empty($id)) {
			return false;
		}

		$created = date('Y-m-d H:i:s', time());
		$name = $this->get_name();
		$errors = $this->get_errors();
		$table = $this->get_table();

		$data_json = json_encode($data);
		$errors_json = json_encode($errors);

		$sql = "INSERT INTO {$table}
				(created, id, name, data, errors, status)
			VALUES
				(:created, :id, :name, :data, :errors, :status)";

		$pdo->beginTransaction();
			$stmt = $pdo->prepare($sql);

			if (!$stmt) {
				$pdo->rollback();
				return false;
			}

			$parameters = array(
				'created' => $created,
				'id' => $id,
				'name' => $name,
				'data' => $data_json,
				'errors' => $errors_json,
				'status' => 1
			);

			$executed = $stmt->execute($parameters);
		$pdo->commit();

		return $executed;
	}

	public function set_table($table) {
		$this->table = trim($table);
		return $this;
	}

	public function get_pdo() {
		return $this->pdo;
	}

	public function get_table() {
		return $this->table;
	}

}