<?php

declare(encoding='UTF-8');
namespace Jolt;

abstract class FormController {

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

	public function addError($field, $error) {
		$this->errors[$field] = $error;
		return $this;
	}

	public function error($field) {
		if ( array_key_exists($field, $this->errors) ) {
			return $this->errors[$field];
		}
		return NULL;
	}

	public function value($field) {
		if ( array_key_exists($field, $this->data) ) {
			return $this->data[$field];
		}
		return NULL;
	}

	public function setId($id) {
		$this->id = trim($id);
		return $this;
	}

	public function setName($name) {
		$this->name = trim($name);
		return $this;
	}

	public function setData(array $data) {
		$this->data = $data;
		return $this;
	}

	public function setErrors(array $errors) {
		$this->errors = $errors;
		return $this;
	}

	public function getId() {
		return $this->id;
	}

	public function getName() {
		return $this->name;
	}

	public function getData() {
		return $this->data;
	}

	public function getDataCount() {
		return count($this->data);
	}

	public function getErrors() {
		return $this->errors;
	}

	public function getErrorsCount() {
		return count($this->errors);
	}

}