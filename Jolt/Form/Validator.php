<?php

declare(encoding='UTF-8');
namespace Jolt\Form;

class Validator {

	private $ruleSets = array();
	private $error = NULL;

	public function __construct($ruleSets=array(), $error=NULL) {
		foreach ( $ruleSets as $field => $ruleSet ) {
			$this->addRuleSet($field, $ruleSet);
		}
		$this->setError($error);
	}

	public function __destruct() {
		$this->ruleSets = array();
	}

	public function reset() {
		$this->ruleSets = array();
		return $this;
	}

	public function addRuleSet($field, \Jolt\Form\Validator\RuleSet $ruleSet) {
		if ( empty($field) ) {
			throw new \Jolt\Exception('the RuleSet field can not be empty');
		}
		$this->ruleSets[$field] = $ruleSet;
		return $this;
	}

	public function count() {
		return count($this->ruleSets);
	}

	public function isEmpty() {
		return ( 0 === count($this->ruleSets) );
	}

	public function setError($error) {
		$this->error = $error;
		return $this;
	}

	public function getRuleSets() {
		return $this->ruleSets;
	}

	public function getError() {
		return $this->error;
	}

}