<?php

declare(encoding='UTF-8');
namespace Jolt;

class Form {

	private $id = NULL;
	
	private $dataKey = NULL;
	private $exception = NULL;
	
	private $data = array();
	private $validator = array();
	private $messages = array();

	public function __construct() {
	
	}
	
	public function __destruct() {
		$this->data = array();
	}
	
	public function addMessage($message) {
		$this->messages[] = $message;
		return $this;
	}
	
	public function validate() {
		$data = $this->getData();
		if ( 0 == count($data) ) {
			return true;
		}
		
		$this->checkDataAndValidatorCounts();
		$this->checkDataAndValidatorElements();
		
		$emptyRuleSets = $this->checkEmptyRuleSets();
		if ( $emptyRuleSets ) {
			return true;
		}
		
		$invalid = false;
		foreach ( $data as $dataKey => $dataValue ) {
			$ruleSet = $this->validator[$dataKey];
			if ( !$ruleSet->isValid($dataValue) ) {
				$this->addMessage($ruleSet->getMessage());
				$invalid = true;
			}
		}
		
		if ( $invalid ) {
			$this->throwException("The form '{$this->id}' was submitted with errors.");
		}
		
		return $data;
	}
	
	public function setId($id) {
		$this->id = trim($id);
		return $this;
	}
	
	public function setData(array $data) {
		ksort($data);
		$this->data = $data;
		return $this;
	}
	
	public function setDataKey($dataKey) {
		$this->dataKey = trim($dataKey);
		return $this;
	}

	public function setException($exception) {
		$this->exception = trim($exception);
		return $this;
	}

	public function setValidator(array $validator) {
		ksort($validator);
		$this->checkValidatorElementsAreRuleSets($validator);
		
		$this->validator = $validator;
		return $this;
	}
	
	public function getData() {
		if ( !empty($this->dataKey) && array_key_exists($this->dataKey, $this->data) ) {
			return $this->data[$this->dataKey];
		}
		return $this->data;
	}

	public function getMessages() {
		return $this->messages;
	}


	private function checkValidatorElementsAreRuleSets($validator) {
		foreach ( $validator as $ruleKey => $ruleSet ) {
			if ( !($ruleSet instanceof \Jolt\Form\RuleSet) ) {
				$this->throwException("The element '{$ruleKey}' is not of type \Jolt\Form\RuleSet.");
			}
		}
		return true;
	}

	private function checkDataAndValidatorCounts() {
		$data = $this->getData();
		if ( count($data) !== count($this->validator) ) {
			$this->throwException('The number of elements in the data do not match the number of elements in the validator.');
		}
		return true;
	}
	
	private function checkDataAndValidatorElements() {
		$data = $this->getData();
		if ( array_keys($data) !== array_keys($this->validator) ) {
			$this->throwException('The elements of the data do not match the elements of the validator.');
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
	
	private function throwException($message) {
		$exception = $this->exception;
		if ( empty($exception) || !class_exists($exception) ) {
			$exception = '\Jolt\Exception';
		}
		throw new $exception($message);
	}
}