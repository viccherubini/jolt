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
	
	private $pdo = NULL;
	private $saveHandler = NULL;
	private $sessionName = NULL;
	private $sessionId = NULL;

	private $ip = NULL;
	private $agent = NULL;

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
	
	public function setIp($ip) {
		$this->ip = trim($ip);
		return $this;
	}
	
	public function setAgent($agent) {
		$this->agent = trim($agent);
		return $this;
	}
	
	public function attachPdo(\PDO $pdo) {
		$this->pdo = $pdo;
		return $this;
	}
	
	public function start($sessionName=NULL) {
		$started = false;
		
		if ( php_sapi_name() != 'cli' ) {
			if ( 0 == @ini_get('session.auto_start') && !defined('SID') ) {
				if ( !empty($session_name) ) {
					$matchAlphaNum = preg_match('/^[a-zA-Z0-9]+$/', $session_name);
					if ( !$matchAlphaNum || is_numeric($sessionName) ) {
						$sessionName = NULL;
					}
				}
				
				if ( $this->pdo instanceof \PDO ) {
					ini_set('session.save_handler', 'user');
					register_shutdown_function('session_write_close');

					session_set_save_handler(
						array($this, 'open'),
						array($this, 'close'),
						array($this, 'read'),
						array($this, 'write'),
						array($this, 'destroy'),
						array($this, 'gc')
					);
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
	
	
	/**
	 * Request handlers for saving session data to the database.
	 */

	public function open() {
		return true;
	}
	
	public function close() {
		$maxLifetime = (int)get_cfg_var('session.gc_maxlifetime');
		$this->gc($maxLifetime);
		
		return true;
	}
	
	public function read($sessionId) {
		$data = NULL;
		
		try {
			$pdoStatement = $this->pdo
				->prepare('SELECT data FROM session WHERE session_id = :session_id');
			$pdoStatement->execute(array('session_id' => $sessionId));
			$session = $pdoStatement->fetchObject();
			
			if ( is_object($session) && property_exists($session, 'data') ) {
				$data = $session->data;
			}
		} catch ( \Exception $e ) { }
		
		return $data;
	}
	
	public function write($sessionId, $data) {
		$expiration = time();
		$now = date('Y-m-d H:i:s');
		
		try {
			// Find the session first
			$pdoStatement = $this->pdo
				->prepare('SELECT COUNT(*) AS session_count FROM session WHERE session_id = :session_id');
			$pdoStatement->execute(array('session_id' => $sessionId));
			$session = $pdoStatement->fetch(\PDO::FETCH_OBJ);
			
			$sessionCount = 0;
			if ( property_exists($session, 'session_count') ) {
				$sessionCount = (int)$session->session_count;
			}
			
			if ( 1 == $sessionCount ) {
				$parameters = array(
					'updated' => $now,
					'expiration' => $expiration,
					'data' => $data,
					'session_id' => $sessionId
				);
				
				$sql = 'UPDATE session SET
						updated = :updated, expiration = :expiration, data = :data
					WHERE session_id = :session_id';
				
				$pdoStatement = $this->pdo
					->prepare($sql);
				
				$pdoStatement->execute($parameters);
			} elseif ( 0 == $sessionCount ) {
				$parameters = array(
					'session_id' => $sessionId,
					'created' => $now,
					'updated' => '',
					'expiration' => $expiration,
					'ip' => $this->ip,
					'agent' => $this->agent,
					'agent_hash' => sha1($this->agent),
					'data' => $data
				);
				
				$sql = 'INSERT INTO session ( session_id, created, updated, expiration, ip, agent, agent_hash, data )
					VALUES ( :session_id, :created, :updated, :expiration, :ip, :agent, :agent_hash, :data)';
				
				$pdoStatement = $this->pdo
					->prepare($sql);
					
				$pdoStatement->execute($parameters);
			} else {
				//$sql = 'DELETE FROM se
			}
		} catch ( \Exception $e ) { }
		
		return true;
	}
	
	public function destroy($sessionId) {
		try {
			$pdoStatement = $this->pdo
				->prepare('DELETE FROM session WHERE session_id = :session_id');
			$pdoStatement->execute(array('session_id' => $sessionId));
		} catch ( \Exception $e ) { }
		
		return true;
	}
	
	public function gc($lifetime) {
		try {
			$expiration = time() - $lifetime;
			
			$pdoStatement = $this->pdo
				->prepare('DELETE FROM session WHERE expiration < :expiration');
			$pdoStatement->execute(array('expiration' => $expiration));
		} catch ( \Exception $e ) { }
		
		return true;
	}
	
}