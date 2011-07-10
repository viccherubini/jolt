<?php

declare(encoding='UTF-8');
namespace jolt\form\writer;

require_once('jolt/form/writer.php');

class db extends \jolt\form\writer {

	private $pdo = NULL;
	private $table = 'form';

	public function attach_pdo(\jolt\pdo $pdo) {
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

		$pdo->modify('insert into '.$table.' (created, id, name, data, errors, status) values (:created, :id, :name, :data, :errors, :status)',
			array('created' => $created, 'id' => $id, 'name' => $name, 'data' => $data_json, 'errors' => $errors_json, 'status' => 1));

		return true;
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