<?php

declare(encoding='UTF-8');
namespace Jolt\Form;

class Validator {

	private $ruleSets = array();
	
	public function __construct($ruleSets=array()) {
		foreach ( $ruleSets as $field => $ruleSet ) {
			$this->addRuleSet($field, $ruleSet);
		}
	}
	
	public function __destruct() {
		$this->ruleSets = array();
	}
	
	public function addRuleSet($field, \Jolt\Form\Validator\RuleSet $ruleSet) {
		if ( empty($field) ) {
			throw new \Jolt\Exception('the validator ruleset field can not be empty');
		}
		$this->ruleSets[$field] = $ruleSet;
		return $this;
	}
	
	public function isEmpty() {
		return (0 === count($this->ruleSets));
	}
	
	public function getRuleSets() {
		return $this->ruleSets;
	}
	
}