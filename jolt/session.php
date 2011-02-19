<?php

declare(encoding='UTF-8');
namespace jolt;

class session {

	private static $instance = NULL;

	private $pdo = NULL;
	private $save_handler = NULL;

	private $agent = NULL;
	private $agent_hash = NULL;
	private $id = NULL;
	private $ip = NULL;
	private $session_name = NULL;

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


	public function attach_pdo(\jolt\pdo $pdo) {
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

				if (!is_null($this->pdo)) {
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

			$this->id = session_id();
			$this->started = true;
		}

		return $started;
	}

	public function delete() {
		if (array_key_exists($this->session_name, $_COOKIE)) {
			setcookie($this->session_name, $this->id, 1, '/');
		}

		session_destroy();
		return true;
	}

	public function id() {
		return $this->id;
	}


	public function open() {
		return true;
	}

	public function close() {
		$max_lifetime = (int)get_cfg_var('session.gc_maxlifetime');
		$this->gc($max_lifetime);
		return true;
	}

	public function read($id) {
		$pdo = $this->check_pdo();

		$session = $pdo->select_one('SELECT * FROM `session` WHERE id = :id', array(':id' => $id));
		if (is_object($session) && property_exists($session, 'data')) {
			if ($session->agent_hash !== $this->agent_hash) {
				$this->destroy();
				return NULL;
			} else {
				$this->set_agent($session->agent)
					->set_agent_hash($session->agent_hash);
				return $session->data;
			}
		}
		return NULL;
	}

	public function write($id, $data) {
		$pdo = $this->check_pdo();

		$expiration = time();
		$now = date('Y-m-d H:i:s');

		$session = $pdo->select_one('SELECT * FROM `session` WHERE id = :id', array(':id' => $id));
		$session_count = (false === $session ? 0 : 1);

		if (1 === $session_count) {
			$pdo->modify('UPDATE `session` SET updated = :updated, expiration = :expiration, data = :data WHERE id = :id',
				array(':updated' => $now, ':expiration' => $expiration, ':data' => $data, ':id' => $id));
		} elseif (0 === $session_count) {
			$pdo->modify('INSERT INTO `session` (id, created, expiration, ip, agent, agent_hash, data) VALUES (:id, :created, :expiration, :ip, :agent, :agent_hash, :data)',
				array(':id' => $id, ':created' => $now, ':expiration' => $expiration, ':ip' => $this->ip, ':agent' => $this->agent, ':agent_hash' => $this->agent_hash, ':data' => $data));
		}

		return true;
	}

	public function destroy($id) {
		$pdo = $this->check_pdo();
		$pdo->modify('DELETE FROM `session` WHERE id = :id', array(':id' => $id));

		return true;
	}

	public function gc($lifetime) {
		$pdo = $this->check_pdo();

		$expiration = time() - $lifetime;
		$pdo->modify('DELETE FROM `session` WHERE expiration < :expiration', array(':expiration' => $expiration));

		return true;
	}

	public function set_agent($agent) {
		$this->agent = trim($agent);
		$this->set_agent_hash(sha1($agent));
		return $this;
	}

	public function set_agent_hash($agent_hash) {
		$this->agent_hash = trim($agent_hash);
		return $this;
	}

	public function set_ip($ip) {
		$this->ip = trim($ip);
		return $this;
	}

	public function get_agent() {
		return $this->agent;
	}

	public function get_agent_hash() {
		return $this->agent_hash;
	}

	private function check_pdo() {
		if (!($this->pdo instanceof \jolt\pdo)) {
			throw new \jolt\exception('A PDO object is not attached properly.');
		}
		return $this->pdo;
	}

}