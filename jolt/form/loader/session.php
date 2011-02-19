<?php

declare(encoding='UTF-8');
namespace jolt\form\loader;

require_once('jolt/form/loader.php');

class session extends \jolt\form\loader {

	private $session;
	private $key = 'jolt_form';

	public function attach_session(\jolt\session $session) {
		$this->session = $session;
		return $this;
	}

	public function load() {
		$session = $this->get_session();
		if (is_null($session)) {
			return false;
		}

		$id = $this->get_id();
		if (empty($id)) {
			return false;
		}

		$name = $this->get_name();
		if (empty($name)) {
			return false;
		}

		$key = $this->get_key();
		if (isset($session->$key)) {
			$jolt_forms = $session->$key;
			if (array_key_exists($name, $jolt_forms)) {
				$form_data = $jolt_forms[$name];

				$data = json_decode($form_data['data'], true);
				$errors = json_decode($form_data['errors'], true);

				if (is_null($data)) {
					$data = array($form_data['data']);
				}

				if (is_null($errors)) {
					$errors = array($form_data['errors']);
				}

				$this->set_data($data)
					->set_errors($errors);

				unset($jolt_forms[$name]);
				$session->$key = $jolt_forms;

				return true;
			}
		}

		return false;
	}

	public function set_key($key) {
		$this->key = trim($key);
		return $this;
	}

	public function get_key() {
		return $this->key;
	}

	public function get_session() {
		return $this->session;
	}

}