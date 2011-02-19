<?php

declare(encoding='UTF-8');

class array {

	private $length = 0;

	public function __construct($array=array()) {
		foreach ($array as $k => $v) {
			$this->__set($k, $v);
		}
	}

	public function __set($k, $v) {
		if (!property_exists($this, $k)) {
			$this->length++;
		}
		$this->$k = $v;
		return true;
	}

	public function __get($k) {
		$retval = (!property_exists($this, $k) ? NULL : $this->$k);
		return $retval;
	}

	public function length() {
		return $this->length;
	}

}