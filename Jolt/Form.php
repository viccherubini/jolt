<?php

declare(encoding='UTF-8');
namespace Jolt;

class Form {

	private $id = NULL;
	private $name = NULL;
	private $dataKey = NULL;
	
	private $exception = NULL;
	private $loader = NULL;
	private $validator = NULL;
	private $writer = NULL;
	
	private $data = array();
	
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
	
	public function save() {
		
	}
	
	public function validate() {
		
	}
	
	public function message($field) {
		if ( array_key_exists($field, $this->messages) ) {
			return $this->messages[$field];
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
	
	public function setDataKey($dataKey) {
		$this->dataKey = trim($dataKey);
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
	
	public function getDataKey() {
		return $this->dataKey;
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
	
	
	
	
	
	
	


	private function checkDataAndValidatorCounts() {
		$data = $this->getData();
		if ( count($data) !== count($this->validator) ) {
			//$this->throwException('The number of elements in the data do not match the number of elements in the validator.');
		}
		return true;
	}
	
	private function checkDataAndValidatorElements() {
		$data = $this->getData();
		if ( array_keys($data) !== array_keys($this->validator) ) {
			//$this->throwException('The elements of the data do not match the elements of the validator.');
		}
		return true;
	}
	
	private function checkEmptyRuleSets() {
		$allEmpty = true;
		foreach ( $this->validator as $ruleSet ) {
			if ( !$ruleSet->isEmpty() ) {
				$allEmpty = false;
			}
		}
		return $allEmpty;
	}
	
/*
	private function throwException($message) {
		$exception = $this->exception;
		if ( empty($exception) || !class_exists($exception) ) {
			$exception = '\Jolt\Exception';
		}
		throw new $exception($message);
	}
*/
}