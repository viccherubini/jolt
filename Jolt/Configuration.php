<?php

declare(encoding='UTF-8');
namespace Jolt;

class Configuration extends \stdClass {
	
	public function __get($k) {
		$retval = ( !property_exists($this, $k) ? NULL : $this->k );
		return $retval;
	}
}