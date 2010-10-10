<?php

declare(encoding='UTF-8');
namespace Jolt;

class Form {

	private $id = NULL;
	
	private $data = array();
	private $validator = array();

	private $errorList = array();

	public function __construct() {
	
	}
	
	public function __destruct() {
		$this->data = array();
	}
	
	
	public function validate() {
		
		if ( 0 == count($this->data) ) {
			return true;
		}
		
		$this->checkDataAndValidatorCounts();
		$this->checkDataAndValidatorElements();
		
		$emptyRuleSets = $this->checkEmptyRuleSets();
		if ( $emptyRuleSets ) {
			return true;
		}
		
		foreach ( $data as $dataKey => $dataValue ) {
			$ruleSet = $validator[$dataKey];
			
			if ( !$ruleSet->isValid($dataValue) ) {
				
			}
		}
		
		return true;
	}
	
	
	public function setData(array $data) {
		ksort($data);
		$this->data = $data;
		return $this;
	}

	public function setValidator(array $validator) {
		ksort($validator);
		$this->checkValidatorElementsAreRuleSets($validator);
		
		$this->validator = $validator;
		return $this;
	}

	public function getErrorList() {
		return $this->errorList;
	}

	
	private function checkValidatorElementsAreRuleSets($validator) {
		foreach ( $validator as $ruleKey => $ruleSet ) {
			if ( !($ruleSet instanceof \Jolt\Form\RuleSet) ) {
				throw new \Jolt\Exception("The element '{$ruleKey}' is not of type \Jolt\Form\RuleSet.");
			}
		}
		return true;
	}

	private function checkDataAndValidatorCounts() {
		if ( count($this->data) !== count($this->validator) ) {
			throw new \Jolt\Exception('The number of elements in the data do not match the number of elements in the validator.');
		}
		return true;
	}
	
	private function checkDataAndValidatorElements() {
		if ( array_keys($this->data) !== array_keys($this->validator) ) {
			throw new \Jolt\Exception('The elements of the data do not match the elements of the validator.');
		}
		return true;
	}
	
	private function checkEmptyRuleSets() {
		$allEmpty = true;
		foreach ( $this->validator as $ruleSet ) {
			if ( $ruleSet->isEmpty() ) {
				$allEmpty = false;
			}
		}
		return $allEmpty;
	}
}