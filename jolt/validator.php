<?php namespace jolt;
declare(encoding='UTF-8');

require_once('jolt/rule.php');

class validator {

	private $rule_set = '';

	private $data = array();
	private $errors = array();
	private $messages = array();
	private $rule_sets = array();
	
	private $exception = null;
	private $rule = null;

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



	public function attach_exception(\Exception $exception) {
		$this->exception = $exception;
		return $this;
	}



	public function start_rule_set($rule_set) {
		$rule_set = trim($rule_set);
		if (!empty($rule_set)) {
			if (!array_key_exists($rule_set, $this->rule_sets)) {
				$this->rule_sets[$rule_set] = array();
			}
			$this->rule_set = $rule_set;
		}
		return $this;
	}
	
	public function add_message($message) {
		if (!empty($this->rule_set)) {
			$this->messages[$this->rule_set] = trim($message);
		}
		return $this;
	}
	
	
	
	public function start_rule($rule, $field) {
		$this->rule = $rule;
		if ($this->rule_set_exists()) {
			$vr = new \jolt\rule;
			$vr->set_field($field);

			$this->rule_sets[$this->rule_set][$rule] = $vr;
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



	public function validate() {
		$rules = $this->get_rule_set_rules();
		foreach ($rules as $field => $set) {
			$value = null;
			if (array_key_exists($field, $this->data)) {
				$value = $this->data[$field];
			}

			if (!$set->is_valid($value)) {
				$this->errors[$field] = $set->get_error();
			}
		}

		if (count($this->errors) > 0 ) {
			$message = $this->get_message();
			if (empty($message)) {
				$message = "The form {$this->rule_set} failed to validate.";
			}

			$exception = $this->exception;
			if (!is_null($exception)) {
				throw new $exception($message, $this);
			} else {
				throw new \jolt\exception($message);
			}
		}
		
		return true;
	}
	
	
	
	public function set_data(array $data) {
		$this->data = $data;
		return $this;
	}
	
	public function set_rule_set($rule_set) {
		$this->rule_set = trim($rule_set);
		return $this;
	}
	
	
	
	public function get_errors() {
		return $this->errors;
	}

	public function get_message() {
		if (array_key_exists($this->rule_set, $this->messages)) {
			return $this->messages[$this->rule_set];
		}
		return null;
	}
	
	public function get_rule_set_rules() {
		if ($this->rule_set_exists()) {
			return $this->rule_sets[$this->rule_set];
		}
		return array();
	}
	
	

	private function rule_set_exists() {
		return (!empty($this->rule_set) && array_key_exists($this->rule_set, $this->rule_sets));
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