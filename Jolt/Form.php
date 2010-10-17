<?php

declare(encoding='UTF-8');
namespace Jolt;

require_once 'Jolt/FormController.php';

class Form {

	private $exception = NULL;
	private $loader = NULL;
	private $validator = NULL;
	private $writer = NULL;

	private $messages = array();

	public function __construct() {

	}

	public function __destruct() {
		$this->data = array();
	}

	public function addMessage($field, $message) {
		$this->messages[$field] = $message;
		return $this;
	}

	public function attachException(\Exception $exception) {
		$this->exception = $exception;
		return $this;
	}

	public function attachLoader(\Jolt\Form\Loader $loader) {
		$this->loader = $loader;
		return $this;
	}

	public function attachWriter(\Jolt\Form\Writer $writer) {
		$this->writer = $writer;
		return $this;
	}

	public function attachValidator(\Jolt\Form\Validator $validator) {
		$this->validator = $validator;
		return $this;
	}

	public function load() {

	}

	public function write() {

	}

	public function validate() {

	}

	public function message($field) {
		if ( array_key_exists($field, $this->messages) ) {
			return $this->messages[$field];
		}
		return NULL;
	}


	public function getException() {
		return $this->exception;
	}

	public function getDataSet() {
		if ( !empty($this->dataKey) && array_key_exists($this->dataKey, $this->data) ) {
			return $this->data[$this->dataKey];
		}
		return $this->data;
	}

}