<?php

declare(encoding='UTF-8');
namespace Jolt;

class Settings {
	
	public function __get($k) {
		$retval = ( !property_exists($this, $k) ? NULL : $this->k );
		return $retval;
	}

}
