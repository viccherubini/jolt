<?php

declare(encoding='UTF-8');
namespace jolt\form\loader;
use \jolt\form\loader;

require_once('jolt/form/loader.php');

class db extends loader {

	private $pdo = NULL;
	private $table = 'form';

	public function attach_pdo(\PDO $pdo) {
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
		$stmt = $pdo->prepare("SELECT form_id, data, errors FROM {$table} WHERE id = :id AND name = :name AND status = 1");

		if (!$stmt) {
			return false;
		}

		$parameters = array(
			'id' => $id,
			'name' => $name
		);

		$executed = $stmt->execute($parameters);

		if ($executed) {
			$form_data = $stmt->fetchObject();
			if (!$form_data) {
				return false;
			}

			$form_id = $form_data->form_id;

			$sql = "UPDATE {$table} SET status = 0 WHERE form_id = :form_id";
			$stmt = $pdo->prepare($sql);

			if (false !== $stmt) {
				$executed = $stmt->execute(array('form_id' => $form_id));

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
			}
		}

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