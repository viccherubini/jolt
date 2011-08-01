<?php namespace jolt;
declare(encoding='UTF-8');

require_once('jolt/form/loader.php');
require_once('jolt/form/writer.php');

class form {

	private $id = '';
	private $message = '';
	private $name = '';

	private $data = array();
	private $errors = array();

	private $loader = null;
	private $writer = null;

	public function __construct() {
		
	}

	public function __destruct() {
		$this->data = array();
	}
	
	public function __get($k) {
		return $this->value($k);
	}

	public function attach_loader(\jolt\form\loader $loader) {
		$this->loader = $loader;
		return $this;
	}
	
	public function attach_writer(\jolt\form\writer $writer) {
		$this->writer = $writer;
		return $this;
	}

	public function render_messages() {
		$message = $this->get_message();
		$message_html = null;
		if (!empty($message)) {
			$message_html_template = '<div class="messages"><ul class="error"><li>%s</li></ul></div>';
			$message_html = sprintf($message_html_template, $this->get_message());
		}
		
		return $message_html;
	}

	public function load() {
		if (is_null($this->loader)) {
			return false;
		}

		$this->loader->set_id($this->id)
			->set_name($this->name);

		$loaded = $this->loader->load();
		if (!$loaded) {
			return false;
		}

		$this->set_data($this->loader->get_data())
			->set_errors($this->loader->get_errors())
			->set_message($this->loader->get_message());

		return true;
	}

	public function write() {
		if (is_null($this->writer)) {
			return false;
		}

		$this->writer->set_id($this->get_id())
			->set_name($this->get_name())
			->set_data($this->get_data())
			->set_errors($this->get_errors())
			->set_message($this->get_message());

		$written = $this->writer->write();
		return $written;
	}
	
	
	
	public function error($field) {
		if (array_key_exists($field, $this->errors)) {
			return $this->errors[$field];
		}
		return null;
	}

	public function value($field) {
		if (array_key_exists($field, $this->data)) {
			return $this->data[$field];
		}
		return null;
	}
	
	
	
	public function set_id($id) {
		$this->id = trim($id);
		return $this;
	}

	public function set_name($name) {
		$this->name = trim($name);
		return $this;
	}

	public function set_data($data) {
		$this->data = (array)$data;
		return $this;
	}

	public function set_errors($errors) {
		$this->errors = (array)$errors;
		return $this;
	}
	
	public function set_message($message) {
		$this->message = trim($message);
		return $this;
	}
	
	
	
	public function get_id() {
		return $this->id;
	}

	public function get_name() {
		return $this->name;
	}

	public function get_data() {
		return $this->data;
	}

	public function get_errors() {
		return $this->errors;
	}
	
	public function get_message() {
		return $this->message;
	}
	
}