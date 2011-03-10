<?php

declare(encoding='UTF-8');
namespace jolt\form;

require_once('jolt/form/validator/rule.php');

class validator {

	private $rule_set = NULL;
	private $rule = NULL;

	private $rule_sets = array();
	private $errors = array();


	public function __construct() {

	}

	public function __destruct() {

	}

	public function __call($method, $argv) {
		if (!$this->rule_exists()) {
			return $this;
		}

		$method = strtolower($method);
		if (isset($argv[0])) {
			$this->add_rule($method, $argv[0]);

			if (isset($argv[1])) {
				$this->add_error($method, $argv[1]);
			}
		}
		return $this;
	}

	public function rule_set($rule_set) {
		if (!array_key_exists($rule_set, $this->rule_sets)) {
			$this->rule_sets[$rule_set] = array();
		}
		$this->rule_set = $rule_set;
		return $this;
	}

	public function error($error) {
		$this->errors[$this->rule_set] = $error;
		return $this;
	}

	public function rule($rule, $field) {
		$this->rule = $rule;
		if ($this->rule_set_exists()) {
			$validator_rule = new \jolt\form\validator\rule;
			$validator_rule->set_field($field);

			$this->rule_sets[$this->rule_set][$rule] = $validator_rule;
		}
		return $this;
	}

	public function not_empty($error) {
		$this->add_rule('empty', false, $error);
		return $this;
	}

	public function min_max($min, $max, $min_error, $max_error) {
		$this->add_rule('minlength', $min)
			->add_rule('maxlength', $max);
		$this->add_error('minlength', $min_error)
			->add_error('maxlength', $max_error);
		return $this;
	}

	public function empty_array($error) {
		$this->add_rule('callback', function($array) {
			return ((!is_array($array) || 0 === count($array)) ? false : true);
		}, $error);
		return $this;
	}

	public function get_error() {
		if (array_key_exists($this->rule_set, $this->errors)) {
			return $this->errors[$this->rule_set];
		}
		return NULL;
	}

	public function is_empty() {
		if ($this->rule_set_exists()) {
			return (0 === count($this->rule_sets[$this->rule_set]));
		}
		return true;
	}

	public function get_rule_set() {
		if ($this->rule_set_exists()) {
			return $this->rule_sets[$this->rule_set];
		}
		return array();
	}


	private function rule_set_exists() {
		return (array_key_exists($this->rule_set, $this->rule_sets));
	}

	private function rule_exists() {
		return ($this->rule_set_exists() && array_key_exists($this->rule, $this->rule_sets[$this->rule_set]));
	}

	private function add_rule($key, $rule) {
		if ($this->rule_exists()) {
			$this->rule_sets[$this->rule_set][$this->rule]->add_rule($key, $rule);
		}
		return $this;
	}

	private function add_error($key, $error) {
		if ($this->rule_exists()) {
			$this->rule_sets[$this->rule_set][$this->rule]->add_error($key, $error);
		}
		return $this;
	}

}