<?php

declare(encoding='UTF-8');
namespace Jolt;

abstract class FormController {

	private $id = NULL;
	private $dataKey = NULL;
	private $name = NULL;

	private $data = array();
	private $errors = array();

	public function __construct() {

	}

	public function __destruct() {
		$this->reset();
	}

	public function reset() {
		$this->data = array();
		$this->dataKey = NULL;
		$this->id = NULL;
		$this->name = NULL;
	}

	public function setId($id) {
		$this->id = trim($id);
		return $this;
	}

	public function setName($name) {
		$this->name = trim($name);
		return $this;
	}

	public function setDataKey($dataKey) {
		$this->dataKey = trim($dataKey);
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

	public function getDataKey() {
		return $this->dataKey;
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

}