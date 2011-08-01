<?php namespace jolt\form;
declare(encoding='UTF-8');

class loader {

	private $id = '';
	private $name = '';
	private $message = '';
	private $key = 'jolt.form';
	
	private $data = array();
	private $errors = array();

	private $session = null;
	
	public function __construct() {
		
	}
	
	public function __destruct() {
		
	}
	
	public function attach_session(\jolt\session $session) {
		$this->session = $session;
		return $this;
	}

	public function load() {
		if (is_null($this->session)) {
			return false;
		}

		if (empty($this->id)) {
			return false;
		}

		if (empty($this->name)) {
			return false;
		}

		$key = $this->key;
		if (isset($this->session->$key)) {
			$jolt_forms = $this->session->$key;
			if (array_key_exists($this->name, $jolt_forms)) {
				$form_data = $jolt_forms[$this->name];

				$data = json_decode($form_data['data'], true);
				$errors = json_decode($form_data['errors'], true);

				if (is_null($data)) {
					$data = array($form_data['data']);
				}

				if (is_null($errors)) {
					$errors = array($form_data['errors']);
				}

				$this->set_data($data)
					->set_errors($errors)
					->set_message($form_data['message']);

				unset($jolt_forms[$this->name]);
				$this->session->$key = $jolt_forms;

				return true;
			}
		}

		return false;
	}



	public function set_key($key) {
		$this->key = trim($key);
		return $this;
	}
	
	public function set_id($id) {
		$this->id = trim($id);
		return $this;
	}

	public function set_name($name) {
		$this->name = trim($name);
		return $this;
	}

	public function set_data($data) {
		$this->data = (array)$data;
		return $this;
	}

	public function set_errors($errors) {
		$this->errors = (array)$errors;
		return $this;
	}
	
	public function set_message($message) {
		$this->message = trim($message);
		return $this;
	}



	public function get_data() {
		return $this->data;
	}
	
	public function get_errors() {
		return $this->errors;
	}
	
	public function get_message() {
		return $this->message;
	}
	
}