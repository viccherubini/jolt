<?php

declare(encoding='UTF-8');
namespace Jolt;

class Settings {

	public function __construct($settings=array()) {
		foreach ( $settings as $k => $v ) {
			$this->$k = $v;
		}
	}

	public function __get($k) {
		$retval = ( !property_exists($this, $k) ? NULL : $this->$k );
		return $retval;
	}

}