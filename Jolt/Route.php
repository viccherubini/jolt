<?php

declare(encoding='UTF-8');
namespace Jolt;

abstract class Route {

	

	public function __construct() {
		
	}

	public function __destruct() {
		
	}
	

	
	abstract public function isValid();
}