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

	private static $instance = NULL;
	private $sessionName = NULL;
	private $sessionId = NULL;

	private $started = false;

	private function __construct() {
		
	}
	
	private function __clone() {
		
	}
	
	public function __set($k, $v) {
		$_SESSION[$k] = $v;
		return true;
	}
	
	public function __get($k) {
		if ( array_key_exists($k, $_SESSION) ) {
			return $_SESSION[$k];
		}
		return NULL;
	}
	
	public function __isset($k) {
		return array_key_exists($k, $_SESSION);
	}
	
	public function __unset($k) {
		if ( array_key_exists($k, $_SESSION) ) {
			unset($_SESSION[$k]);
		}
		return true;
	}
	
	public static function getInstance() {
		if ( NULL === self::$instance ) {
			self::$instance = new self;
		}
		return self::$instance;
	}
	
	public function start($sessionName = NULL) {
		$started = false;
		
		if ( php_sapi_name() != 'cli' ) {
			if ( 0 == @ini_get('session.auto_start') && !defined('SID') ) {
				if ( !empty($session_name) ) {
					$matchAlphaNum = preg_match('/^[a-zA-Z0-9]+$/', $session_name);
					if ( !$matchAlphaNum || is_numeric($sessionName) ) {
						$sessionName = NULL;
					}
				}

				if ( !empty($sessionName) ) {
					$this->sessionName = $sessionName;
					session_name($sessionName);
				} else {
					$this->sessionName = session_name();
				}
				
				$started = session_start();
			} else {
				$started = true;
			}
			
			$this->sessionId = session_id();
			$this->started = true;
		}
		
		return $started;
	}
	
	public function delete() {
		if ( isset($_COOKIE[$this->sessionName]) ) {
			setcookie($this->sessionName, $this->sessionId, 1, '/');
		}

		session_destroy();
		
		return true;

	}
	
}