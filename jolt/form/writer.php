<?php

declare(encoding='UTF-8');
namespace jolt\form;

require_once('jolt/form_controller.php');

class writer extends \jolt\form_controller {

	private $session = NULL;
	private $key = 'jolt.form';

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
		$id = $this->get_id();
		if (empty($id)) {
			return false;
		}

		$name = $this->get_name();
		$errors = $this->get_errors();
		$message = $this->get_message();

		$data_json = json_encode($data);
		$errors_json = json_encode($errors);
		
		$jolt_forms = array();
		$key = $this->get_key();
		if (isset($session->$key)) {
			$jolt_forms = $session->$key;
		}

		$jolt_forms[$name] = array(
			'data' => $data_json,
			'errors' => $errors_json,
			'message' => $message
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