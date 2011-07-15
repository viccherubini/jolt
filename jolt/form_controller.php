<?php namespace jolt;
declare(encoding='UTF-8');

abstract class form_controller {

	private $id = null;
	private $name = null;

	private $data = array();
	private $errors = array();

	private $message = null;

	public function __construct() {

	}

	public function __destruct() {
		$this->reset();
	}

	public function reset() {
		$this->id = null;
		$this->name = null;

		$this->data = array();
		$this->errors = array();
	}

	public function add_error($field, $error) {
		$this->errors[$field] = $error;
		return $this;
	}

	public function error($field) {
		if (array_key_exists($field, $this->errors)) {
			return $this->errors[$field];
		}
		return null;
	}

	public function value($field) {
		if (array_key_exists($field, $this->data)) {
			return $this->data[$field];
		}
		return null;
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

	public function get_id() {
		return $this->id;
	}

	public function get_name() {
		return $this->name;
	}

	public function get_data() {
		return $this->data;
	}

	public function get_data_count() {
		return count($this->data);
	}

	public function get_errors() {
		return $this->errors;
	}

	public function get_errors_count() {
		return count($this->errors);
	}
	
	public function get_message() {
		return $this->message;
	}

}