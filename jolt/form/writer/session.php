<?php

declare(encoding='UTF-8');
namespace jolt\form\writer;

require_once('jolt/form/writer.php');

class session extends \jolt\form\writer {

	private $session = NULL;
	private $key = 'jolt_form';

	public function attach_session(\jolt\session $session) {
		$this->session = $session;
		return $this;
	}

	public function write() {
		$session = $this->get_session();
		if (is_null($session)) {
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

		$name = $this->get_name();
		$errors = $this->get_errors();

		$data_json = json_encode($data);
		$errors_json = json_encode($errors);

		$jolt_forms = array();
		$key = $this->get_key();
		if (isset($session->$key)) {
			$jolt_forms = $session->$key;
		}

		$jolt_forms[$name] = array(
			'data' => $data_json,
			'errors' => $errors_json
		);

		$session->$key = $jolt_forms;

		return true;
	}

	public function set_key($key) {
		$this->key = trim($key);
		return $this;
	}

	public function get_key() {
		return $this->key;
	}

	public function get_session() {
		return $this->session;
	}

}