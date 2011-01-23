<?php

declare(encoding='UTF-8');
namespace jolt;

abstract class form_controller {

	private $id = NULL;
	private $name = NULL;

	private $data = array();
	private $errors = array();

	public function __construct() {

	}

	public function __destruct() {
		$this->reset();
	}

	public function reset() {
		$this->id = NULL;
		$this->name = NULL;

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
		return NULL;
	}

	public function value($field) {
		if (array_key_exists($field, $this->data)) {
			return $this->data[$field];
		}
		return NULL;
	}

	public function set_id($id) {
		$this->id = trim($id);
		return $this;
	}

	public function set_name($name) {
		$this->name = trim($name);
		return $this;
	}

	public function set_data(array $data) {
		$this->data = $data;
		return $this;
	}

	public function set_errors(array $errors) {
		$this->errors = $errors;
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

}