<?php namespace jolt;
declare(encoding='UTF-8');

class vo {

	private $_value_keys = array();

	public function __construct($array=array()) {
		if (is_array($array) || is_object($array)) {
			foreach ($array as $k => $v) {
				$this->__set($k, $v);
			}
		}
	}

	public function __set($k, $v) {
		$this->$k = $v;
		if (!array_key_exists($k, $this->_value_keys)) {
			$this->_value_keys[$k] = true;
		}

		return true;
	}

	public function __get($k) {
		$retval = (!property_exists($this, $k) ? null : $this->$k);
		return $retval;
	}

	public function to_array() {
		$value_array = array();
		foreach ($this as $k => $v) {
			if (array_key_exists($k, $this->_value_keys)) {
				$value_array[$k] = $v;
			}
		}

		return $value_array;
	}
}