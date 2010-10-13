<?php

declare(encoding='UTF-8');
namespace Jolt\Form\Validator;

class RuleSet {

	private $charset = 'UTF-8';

	private $field = NULL;

	private $rules = array();
	private $messages = array();
	
	public function __construct(array $rules, array $messages=array(), $field=NULL) {
		$this->rules = $rules;
		$this->messages = $messages;
		$this->field = $field;
	}
	
	public function __destruct() {
		$this->rules = array();
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
				break;
			}
		}
		return $isValid;
	}
	
	private function op_empty($empty, $value) {
		if ( empty($value) ) {
			//$this->setMessage(sprintf('The field %s can not be empty.', $this->field));
			return false;
		}
		return true;
	}
	
	private function op_minlength($minlength, $value) {
		$minlength = (int)$minlength;
		if ( mb_strlen($value, $this->charset) < $minlength ) {
			//$this->setMessage(sprintf('The field %s must have a length greater than or equal to %d characters.', $this->field, $minlength));
			return false;
		}
		return true;
	}
	
	private function op_maxlength($maxlength, $value) {
		$maxlength = (int)$maxlength;
		if ( mb_strlen($value, $this->charset) > $maxlength ) {
			//$this->setMessage(sprintf('The field %s must have a length less than or equal to %d characters.', $this->field, $maxlength));
			return false;
		}
		return true;
	}
	
	private function op_nonzero($nonzero, $value) {
		$value = (int)$value;
		if ( 0 === $value ) {
			//$this->setMessage(sprintf('The field %s can not have a value of 0.', $this->field));
			return false;
		}
		return true;
	}
	
	private function op_numeric($numeric, $value) {
		if ( !is_numeric($value) ) {
			//$this->setMessage(sprintf('The field %s must be numeric', $this->field));
			return false;
		}
		return true;
	}
	
	private function op_email($email, $value) {
		return true;
	}
	
	private function op_inarray($inarray, $value) {
		if ( !in_array($value, $inarray) ) {
			//$this->setMessage(sprintf('An invalid value was specified for the field %s.', $this->field));
			return false;
		}
		return true;
	}
	
	private function op_regex($regex, $value) {
		if ( 1 !== preg_match($regex, $value) ) {
			//$this->setMessage(sprintf('An invalid value was specified for the field %s.', $this->field));
			return false;
		}
		return true;
	}
	
	private function op_callback($callback, $value) {
		if ( !$callback($value) ) {
			//$this->setMessage(sprintf('An invalid value was specified for the field %s.', $this->field));
			return false;
		}
		return true;
	}
	
}