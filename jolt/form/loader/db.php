<?php

declare(encoding='UTF-8');
namespace jolt\form\loader;

require_once('jolt/form/loader.php');

class db extends \jolt\form\loader {

	private $pdo = NULL;
	private $table = 'form';

	public function attach_pdo(\jolt\pdo $pdo) {
		$pdo->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_SILENT);
		$pdo->setAttribute(\PDO::ATTR_EMULATE_PREPARES, false);
		$this->pdo = $pdo;
		return $this;
	}

	public function load() {
		$pdo = $this->get_pdo();
		if (is_null($pdo)) {
			return false;
		}

		$id = $this->get_id();
		if (empty($id)) {
			return false;
		}

		$name = $this->get_name();
		if (empty($name)) {
			return false;
		}

		$table = $this->get_table();
		$form_data = $pdo->select_one('select form_id, data, errors from '.$table.' where id = :id and name = :name and status = :status',
			array(':id' => $id, ':name' => $name, ':status' => 1));

		$form_data = $stmt->fetchObject();
		if (!$form_data) {
			return false;
		}

		$form_id = $form_data->form_id;

		$pdo->modify('update '.$table.' set status = :status where form_id = :form_id',
			array(':status' => 0, ':form_id' => $form_id));

		$data = json_decode($form_data->data, true);
		$errors = json_decode($form_data->errors, true);

		if (is_null($data)) {
			$data = array($form_data->data);
		}

		if (is_null($errors)) {
			$errors = array($form_data->errors);
		}

		$this->set_data($data)
			->set_errors($errors);

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