<?php

declare(encoding='UTF-8');
namespace Jolt;

class Configuration extends \stdClass {
	
	public function __get($k) {
		if ( property_exists($this, $k) ) {
			return $this->$k;
		}
		return NULL;
	}	
}
