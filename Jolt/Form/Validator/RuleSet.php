<?php

declare(encoding='UTF-8');
namespace Jolt\Form\Validator;

class RuleSet {

	private $charset = 'UTF-8';

	private $field = NULL;
	private $error = NULL;

	private $errors = array();
	private $rules = array();

	public function __construct(array $rules, array $errors=array(), $field=NULL) {
		foreach ( $rules as $ruleKey => $ruleValue ) {
			$this->addRule($ruleKey, $ruleValue);
			if ( array_key_exists($ruleKey, $errors) ) {
				$this->addError($ruleKey, $errors[$ruleKey]);
			}
		}

		$this->field = $field;
	}

	public function __destruct() {
		$this->rules = array();
	}

	public function reset() {
		$this->errors = array();
		$this->rules = array();
		return $this;
	}

	public function addRule($ruleKey, $rule) {
		if ( empty($ruleKey) ) {
			throw new \Jolt\Exception('the rule can not be empty');
		}
		$this->rules[$ruleKey] = $rule;
		return $this;
	}

	public function addError($ruleKey, $error) {
		if ( empty($ruleKey) ) {
			throw new \Jolt\Exception('the ruleset errors can not be empty');
		}
		$this->errors[$ruleKey] = $error;
		return $this;
	}

	public function isEmpty() {
		return ( 0 === count($this->rules) );
	}

	public function isValid($value) {
		$isValid = true;
		$this->message = NULL;
		foreach ( $this->rules as $op => $rule ) {
			$opMethod = 'op' . ucwords(strtolower($op));
			if ( method_exists($this, $opMethod) && !$this->$opMethod($rule, $value) ) {
				$isValid = false;
				if ( array_key_exists($op, $this->errors) ) {
					$this->error = sprintf($this->errors[$op], $this->field);
				}
				break;
			}
		}
		return $isValid;
	}

	public function setField($field) {
		$this->field = trim($field);
		return $this;
	}

	public function getError() {
		return $this->error;
	}

	public function getField() {
		return $this->field;
	}

	public function getErrors() {
		return $this->errors;
	}

	public function notEmpty($error) {
		$this->addRule('empty', false)
			->addError('empty', $error);
		return $this;
	}

	public function minMax($min, $max, $minError, $maxError) {
		$this->addRule('minlength', (int)$min)
			->addRule('maxlength', (int)$max)
			->addError('minlength', $minError)
			->addError('maxlength', $maxError);
		return $this;
	}



	private function opEmpty($empty, $value) {
		if ( empty($value) ) {
			return false;
		}
		return true;
	}

	private function opMinlength($minlength, $value) {
		$minlength = (int)$minlength;
		if ( mb_strlen($value, $this->charset) < $minlength ) {
			return false;
		}
		return true;
	}

	private function opMaxlength($maxlength, $value) {
		$maxlength = (int)$maxlength;
		if ( mb_strlen($value, $this->charset) > $maxlength ) {
			return false;
		}
		return true;
	}

	private function opNonzero($nonzero, $value) {
		if ( !is_numeric($value) ) {
			$value = (int)$value;
		}

		if ( 0 === $value ) {
			return false;
		}
		return true;
	}

	private function opNumeric($numeric, $value) {
		if ( !is_numeric($value) ) {
			return false;
		}
		return true;
	}

	private function opEmail($email, $value) {
		// We're not very strict about email addresses. Essentially: anything@anything
		$value = trim($value);
		if ( false === stripos($value, '@') ) {
			return false;
		}

		// Must have something on left hand side and something on right hand side
		$bits = explode('@', $value);

		// Count must be exactly 2, can't have multiple @ signs
		if ( 2 !== count($bits) ) {
			return false;
		}

		// Length of each side must be greater than 0
		return ( mb_strlen($bits[0], $this->charset) > 0 && mb_strlen($bits[1], $this->charset) );
	}

	private function opInarray($inarray, $value) {
		if ( !in_array($value, $inarray, true) ) {
			return false;
		}
		return true;
	}

	private function opRegex($regex, $value) {
		if ( 1 !== preg_match($regex, $value) ) {
			return false;
		}
		return true;
	}

	private function opCallback($callback, $value) {
		if ( !$callback($value) ) {
			return false;
		}
		return true;
	}

}