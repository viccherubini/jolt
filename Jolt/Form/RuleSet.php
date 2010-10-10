<?php

declare(encoding='UTF-8');
namespace Jolt\Form;

class RuleSet {

	private $ruleSet = array();
	private $field = NULL;

	public function __construct(array $ruleSet, $field=NULL) {
		$this->ruleSet = $ruleSet;
		$this->field = $field;
	}
	
	public function __destruct() {
		$this->ruleSet = array();
	}
	
	public function isEmpty() {
		return ( 0 === count($this->ruleSet) );
	}
	
	public function isValid($value) {
		
		$isValid = true;
		foreach ( $this->ruleSet as $op => $rule ) {
			$opMethod = 'op_' . strtolower($op);
			if ( method_exists($this, $opMethod) && !$this->$opMethod($rule, $value) ) {
				$isValid = false;
			}
		}
		
		return $isValid;
	}
	
	public function getField() {
		return $this->field;
	}
	
	public function getRuleSet() {
		return $this->ruleSet;
	}
	
	private function op_empty($empty, $value) {
		
	}
	
	private function op_minlength($minlength, $value) {
		$minlength = (int)$minlength;
		return ( mb_strlen($value, 'UTF-8') >= $minlength );
	}
	
	private function op_maxlength($maxlength, $value) {
		$maxlength = (int)$maxlength;
		return ( mb_strlen($value, 'UTF-8') <= $maxlength );
	}
	
	private function op_nonzero($nonzero, $value) {
		$value = (int)$value;
		return ( 0 === $value ? false : true );
	}
	
	private function op_numeric($numeric, $value) {
		return ( is_numeric($value) );
	}
	
	private function op_email($email, $value) {
		return true;
	}
	
	private function op_inarray($inarray, $value) {
		return ( in_array($value, $inarray) );
	}
	
	private function op_regex($regex, $value) {
		return ( 1 === preg_match($regex, $value) );
	}
	
	private function op_callback($callback, $value) {
		return ( $callback($value) );
	}
	
	
	
	
}