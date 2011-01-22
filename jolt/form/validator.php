<?php

declare(encoding='UTF-8');
namespace Jolt\Form;

require_once 'jolt/form/validator/rule.php';

class Validator {

	private $ruleSet = NULL;
	private $rule = NULL;

	private $ruleSets = array();
	private $errors = array();


	public function __construct() {

	}

	public function __destruct() {

	}

	public function __call($method, $argv) {
		if ( !$this->ruleExists() ) {
			return $this;
		}

		$method = strtolower($method);
		if ( isset($argv[0]) ) {
			$this->addRule($method, $argv[0]);

			if ( isset($argv[1]) ) {
				$this->addError($method, $argv[1]);
			}
		}
		return $this;
	}

	public function ruleSet($ruleSet) {
		if ( !array_key_exists($ruleSet, $this->ruleSets) ) {
			$this->ruleSets[$ruleSet] = array();
		}
		$this->ruleSet = $ruleSet;
		return $this;
	}

	public function error($error) {
		$this->errors[$this->ruleSet] = $error;
		return $this;
	}

	public function rule($rule, $field) {
		$this->rule = $rule;
		if ( $this->ruleSetExists() ) {
			$validatorRule = new \Jolt\Form\Validator\Rule;
			$validatorRule->setField($field);

			$this->ruleSets[$this->ruleSet][$rule] = $validatorRule;
		}
		return $this;
	}

	public function notEmpty($error) {
		$this->addRule('empty', false, $error);
		return $this;
	}

	public function minMax($min, $max, $minError, $maxError) {
		$this->addRule('minlength', $min, $minError)
			->addRule('maxlength', $max, $maxError);
		return $this;
	}

	public function emptyArray($error) {
		$this->addRule('callback', function($array) {
			return ((!is_array($array) || 0 === count($array)) ? false : true);
		}, $error);
		return $this;
	}

	public function getError() {
		if ( array_key_exists($this->ruleSet, $this->errors) ) {
			return $this->errors[$this->ruleSet];
		}
		return NULL;
	}

	public function is_empty() {
		if ( $this->ruleSetExists() ) {
			return ( 0 === count($this->ruleSets[$this->ruleSet]) );
		}
		return true;
	}

	public function getRuleSet() {
		if ( $this->ruleSetExists() ) {
			return $this->ruleSets[$this->ruleSet];
		}
		return array();
	}


	private function ruleSetExists() {
		return (array_key_exists($this->ruleSet, $this->ruleSets));
	}

	private function ruleExists() {
		return ($this->ruleSetExists() && array_key_exists($this->rule, $this->ruleSets[$this->ruleSet]));
	}

	private function addRule($ruleKey, $rule) {
		if ( $this->ruleExists() ) {
			$this->ruleSets[$this->ruleSet][$this->rule]->addRule($ruleKey, $rule);
		}
		return $this;
	}

	private function addError($ruleKey, $error) {
		if ( $this->ruleExists() ) {
			$this->ruleSets[$this->ruleSet][$this->rule]->addError($ruleKey, $error);
		}
		return $this;
	}

}
