<?php

declare(encoding='UTF-8');
namespace Jolt\Form\Validator;

class Rule {

	private $charset = 'UTF-8';

	private $field = NULL;
	private $error = NULL;

	private $errors = array();
	private $rules = array();

	public function __construct() {

	}

	public function __destruct() {
		$this->rules = array();
	}

	public function addRule($ruleKey, $rule) {
		if ( !empty($ruleKey) ) {
			$this->rules[$ruleKey] = $rule;
		}
		return $this;
	}

	public function addError($ruleKey, $error) {
		if ( !empty($ruleKey) ) {
			$this->errors[$ruleKey] = $error;
		}
		return $this;
	}

	public function isEmpty() {
		return ( 0 === count($this->rules) );
	}

	public function isValid($value) {
		$isValid = true;
		foreach ( $this->rules as $op => $rule ) {
			$opMethod = 'op_' . strtolower($op);
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

	private function op_empty($empty, $value) {
		if ( empty($value) ) {
			return false;
		}
		return true;
	}

	private function op_equal($equalTo, $value) {
		return ( $equalTo === $value );
	}

	private function op_minlength($minlength, $value) {
		$minlength = (int)$minlength;
		if ( mb_strlen($value, $this->charset) < $minlength ) {
			return false;
		}
		return true;
	}

	private function op_maxlength($maxlength, $value) {
		$maxlength = (int)$maxlength;
		if ( mb_strlen($value, $this->charset) > $maxlength ) {
			return false;
		}
		return true;
	}

	private function op_nonzero($nonzero, $value) {
		if ( !is_numeric($value) ) {
			$value = (int)$value;
		}

		if ( 0 === $value ) {
			return false;
		}
		return true;
	}

	private function op_numeric($numeric, $value) {
		if ( !is_numeric($value) ) {
			return false;
		}
		return true;
	}

	private function op_email($email, $value) {
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

	private function op_inarray($inarray, $value) {
		if ( !in_array($value, $inarray, true) ) {
			return false;
		}
		return true;
	}

	private function op_regex($regex, $value) {
		if ( 1 !== preg_match($regex, $value) ) {
			return false;
		}
		return true;
	}

	private function op_callback($callback, $value) {
		if ( !$callback($value) ) {
			return false;
		}
		return true;
	}

}