<?php

declare(encoding='UTF-8');
namespace jolt\form\validator;

class rule {

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

	public function add_rule($key, $rule) {
		if (!empty($key)) {
			$this->rules[$key] = $rule;
		}
		return $this;
	}

	public function add_error($key, $error) {
		if (!empty($key)) {
			$this->errors[$key] = $error;
		}
		return $this;
	}

	public function is_empty() {
		return (0 === count($this->rules));
	}

	public function is_valid($value) {
		$is_valid = true;
		foreach ($this->rules as $op => $rule) {
			$op_method = 'op_' . strtolower($op);
			if (method_exists($this, $op_method) && !$this->$op_method($rule, $value)) {
				$is_valid = false;
				if (array_key_exists($op, $this->errors)) {
					if ('minlength' === $op || 'maxlength' === $op) {
						$this->error = sprintf($this->errors[$op], $this->field, $rule);
					} else {
						$this->error = sprintf($this->errors[$op], $this->field);
					}
				}
				break;
			}
		}
		return $is_valid;
	}

	public function set_field($field) {
		$this->field = trim($field);
		return $this;
	}

	public function get_error() {
		return $this->error;
	}

	public function get_field() {
		return $this->field;
	}

	public function get_errors() {
		return $this->errors;
	}

	private function op_empty($empty, $value) {
		if (empty($value)) {
			return false;
		}
		return true;
	}

	private function op_equal($equal_to, $value) {
		return ($equal_to === $value);
	}

	private function op_minlength($minlength, $value) {
		$minlength = (int)$minlength;
		if (mb_strlen($value, $this->charset) < $minlength) {
			return false;
		}
		return true;
	}

	private function op_maxlength($maxlength, $value) {
		$maxlength = (int)$maxlength;
		if (mb_strlen($value, $this->charset) > $maxlength) {
			return false;
		}
		return true;
	}

	private function op_nonzero($nonzero, $value) {
		$value = (int)$value;
		if (0 === $value) {
			return false;
		}
		return true;
	}

	private function op_numeric($numeric, $value) {
		if (!is_numeric($value)) {
			return false;
		}
		return true;
	}

	private function op_email($email, $value) {
		// We're not very strict about email addresses. Essentially: anything@anything
		$value = trim($value);
		if (false === stripos($value, '@')) {
			return false;
		}

		// Must have something on left hand side and something on right hand side
		$bits = explode('@', $value);

		// Count must be exactly 2, can't have multiple @ signs
		if (2 !== count($bits)) {
			return false;
		}

		// Length of each side must be greater than 0
		return (mb_strlen($bits[0], $this->charset) > 0 && mb_strlen($bits[1], $this->charset) > 0);
	}

	private function op_inarray($inarray, $value) {
		if (!in_array($value, $inarray, true)) {
			return false;
		}
		return true;
	}

	private function op_regex($regex, $value) {
		if (1 !== preg_match($regex, $value)) {
			return false;
		}
		return true;
	}

	private function op_url($is_url, $url) {
		if (false === filter_var($url, FILTER_VALIDATE_URL)) {
			return false;
		}
		return true;
	}

	private function op_callback($callback, $value) {
		if (!$callback($value)) {
			return false;
		}
		return true;
	}

}