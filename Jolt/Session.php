<?php

declare(encoding='UTF-8');
namespace Jolt;

/**
 * Singleton for handling sessions easily. Provides a consistent and clean
 * API for easy session handling.
 *
 * @author vmc <vmc@leftnode.com>
 */
class Session {
	private $instance = NULL;


	private function __construct() {
		
	}
	
	private function __clone() {
		
	}
	
	public static function getInstance() {
		if ( NULL === self::$instance ) {
			self::$instance = new self;
		}
		return self::$instance;
	}
	
	
}