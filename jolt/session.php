<?php

declare(encoding='UTF-8');
namespace jolt;

class session {

	private static $instance = NULL;

	private $pdo = NULL;
	private $save_handler = NULL;
	private $session_name = NULL;
	private $session_id = NULL;

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
		if (array_key_exists($k, $_SESSION)) {
			return $_SESSION[$k];
		}
		return NULL;
	}

	public function __isset($k) {
		return array_key_exists($k, $_SESSION);
	}

	public function __unset($k) {
		if (array_key_exists($k, $_SESSION)) {
			unset($_SESSION[$k]);
		}
		return true;
	}

	public static function get_instance() {
		if (NULL === self::$instance) {
			self::$instance = new self;
		}
		return self::$instance;
	}


	public function attach_pdo(\PDO $pdo) {
		$this->pdo = $pdo;
		return $this;
	}

	public function start($session_name=NULL) {
		$started = false;

		if (php_sapi_name() != 'cli') {
			if (0 == @ini_get('session.auto_start') && !defined('SID')) {
				if (!empty($session_name)) {
					$matched_alpha_num = preg_match('/^[a-zA-Z0-9]+$/', $session_name);
					if (!$matched_alpha_num || is_numeric($session_name)) {
						$session_name = NULL;
					}
				}

				if ($this->pdo instanceof \PDO) {
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

				if (!empty($session_name)) {
					$this->session_name = $session_name;
					session_name($session_name);
				} else {
					$this->session_name = session_name();
				}

				$started = session_start();
			} else {
				$started = true;
			}

			$this->session_id = session_id();
			$this->started = true;
		}

		return $started;
	}

	public function delete() {
		if (array_key_exists($this->session_name, $_COOKIE)) {
			setcookie($this->session_name, $this->session_id, 1, '/');
		}

		session_destroy();
		return true;
	}

	public function open() {
		return true;
	}

	public function close() {
		$max_lifetime = (int)get_cfg_var('session.gc_maxlifetime');
		$this->gc($max_lifetime);
		return true;
	}

	public function read($session_id) {
		$data = NULL;

		$statement = $this->pdo->prepare('SELECT data FROM session WHERE session_id = :session_id');
		$statement->bindValue(':session_id', $session_id, \PDO::PARAM_STR);
		$statement->execute();

		$session = $statement->fetchObject();
		if (is_object($session) && property_exists($session, 'data')) {
			$data = $session->data;
		}
		return $data;
	}

	public function write($session_id, $data) {
		$expiration = time();
		$now = date('Y-m-d H:i:s');

		// Find the session first
		$statement = $this->pdo->prepare('SELECT COUNT(*) AS session_count FROM session WHERE session_id = :session_id');
		$statement->bindValue(':session_id', $session_id, \PDO::PARAM_STR);
		$statement->execute();

		$session = $statement->fetchObject();

		$session_count = 0;
		if (property_exists($session, 'session_count')) {
			$session_count = (int)$session->session_count;
		}

		if (1 == $session_count) {
			$statement = $this->pdo->prepare('UPDATE session SET updated = :updated, expiration = :expiration, data = :data WHERE session_id = :session_id');
			$statement->bindValue(':updated', $now, \PDO::PARAM_STR);
			$statement->bindValue(':expiration', $expiration, \PDO::PARAM_INT);
			$statement->bindValue(':data', $data, \PDO::PARAM_LOB);
			$statement->bindValue(':session_id', $session_id, \PDO::PARAM_STR);

			$statement->execute();
		} elseif (0 == $session_count) {
			$agent_hash = sha1($this->agent);

			$sql = 'INSERT INTO session (session_id, created, updated, expiration, ip, agent, agent_hash, data) VALUES (:session_id, :created, :updated, :expiration, :ip, :agent, :agent_hash, :data)';
			$statement = $this->pdo->prepare($sql);
			$statement->bindValue(':session_id', $session_id, \PDO::PARAM_STR);
			$statement->bindValue(':created', $now, \PDO::PARAM_STR);
			$statement->bindValue(':updated', '', \PDO::PARAM_STR);
			$statement->bindValue(':expiration', $expiration, \PDO::PARAM_INT);
			$statement->bindValue(':ip', $this->ip, \PDO::PARAM_STR);
			$statement->bindValue(':agent', $this->agent, \PDO::PARAM_STR);
			$statement->bindValue(':agent_hash', $agent_hash, \PDO::PARAM_STR);
			$statement->bindValue(':data', $data, \PDO::PARAM_LOB);

			$exec = $statement->execute($parameters);
		}

		return true;
	}

	public function destroy($session_id) {
		$statement = $this->pdo->prepare('DELETE FROM session WHERE session_id = :session_id');
		$statement->bindValue(':session_id', $session_id, \PDO::PARAM_STR);
		$statement->execute();
		return true;
	}

	public function gc($lifetime) {
		$expiration = time() - $lifetime;

		$statement = $this->pdo->prepare('DELETE FROM session WHERE expiration < :expiration');
		$statement->bindValue(':expiration', $expiration, \PDO::PARAM_INT);
		$statement->execute();
		return true;
	}

	public function set_ip($ip) {
		$this->ip = trim($ip);
		return $this;
	}

	public function set_agent($agent) {
		$this->agent = trim($agent);
		return $this;
	}

}