<?php

declare(encoding='UTF-8');
namespace Jolt;

require_once 'Exception.php';

/**
 * This class is an entrance for building an application. It is entirely
 * static and gives you access to building new objects, starting the 
 * application, and basic CSRF protection.
 * 
 * @author vmc <vmc@leftnode.com>
 */
class Jolt {
	
	private $configuration = NULL;
	private $client = NULL;
	private $dispatcher = NULL;
	private $router = NULL;
	
	public function __construct() {

	}
	
	public function attachConfiguration(\Jolt\Configuration $cfg) {
		$this->configuration = clone $cfg;
		return $this;
	}
}
