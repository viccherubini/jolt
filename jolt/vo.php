<?php

declare(encoding='UTF-8');
namespace jolt;

class vo {

	public function __construct($array=array()) {
		if (is_array($array)) {
			foreach ($array as $k => $v) {
				$this->__set($k, $v);
			}
		}
	}

	public function __set($k, $v) {
		$this->$k = $v;
		return true;
	}

	public function __get($k) {
		$retval = (!property_exists($this, $k) ? NULL : $this->$k);
		return $retval;
	}

}