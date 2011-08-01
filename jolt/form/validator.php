<?php namespace jolt\form;
declare(encoding='UTF-8');

require_once('jolt/form/validator/rule.php');

class validator {

	private $data = array();
	private $errors = array();
	private $messages = array();
	private $rule_sets = array();
	
	private $exception = null;
	private $rule_set = null;
	private $rule = null;



	public function __construct() {
		
	}
	
	public function __destruct() {
		$this->reset();
	}



	// Quickly add rules
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
	
	public function message($message) {
		$this->messages[$this->rule_set] = $message;
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
	
	public function rule_set($rule_set) {
		if (!array_key_exists($rule_set, $this->rule_sets)) {
			$this->rule_sets[$rule_set] = array();
		}
		$this->rule_set = $rule_set;
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
	

	
	public function set_name($name) {
		$this->name = trim($name);
		return $this;
	}

	public function set_data(array $data) {
		$this->data = $data;
		return $this;
	}

	public function set_errors(array $errors) {
		$this->errors = $errors;
		return $this;
	}
	
	

	public function get_name() {
		return $this->name;
	}
	
	public function get_data() {
		return $this->data;
	}
	
	public function get_errors() {
		return $this->errors;
	}
	
	public function get_error() {
		if (array_key_exists($this->rule_set, $this->errors)) {
			return $this->errors[$this->rule_set];
		}
		return null;
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



	public function is_empty() {
		if ($this->rule_set_exists()) {
			return (0 === count($this->rule_sets[$this->rule_set]));
		}
		return true;
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