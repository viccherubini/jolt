<?php namespace jolt\form;
declare(encoding='UTF-8');

require_once('jolt/form_controller.php');

class writer {

	private $id = '';
	private $name = '';
	private $message = '';
	private $key = 'jolt.form';
	
	private $data = array();
	private $errors = array();

	private $session = null;

	public function attach_session(\jolt\session $session) {
		$this->session = $session;
		return $this;
	}

	public function write() {
		if (is_null($this->session)) {
			return false;
		}

		if (empty($this->id)) {
			return false;
		}

		$jolt_forms = array();
		
		$key = $this->key;
		if (isset($this->session->$key)) {
			$jolt_forms = $this->session->$key;
		}

		$jolt_forms[$this->name] = array(
			'data' => json_encode($this->data),
			'errors' => json_encode($this->errors),
			'message' => $this->message
		);

		$this->session->$key = $jolt_forms;

		return true;
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

}