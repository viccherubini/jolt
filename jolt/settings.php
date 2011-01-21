<?php

declare(encoding='UTF-8');
namespace jolt;

class settings {

	private $length = 0;
	
	public function __construct($settings=array()) {
		foreach ($settings as $k => $v) {
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