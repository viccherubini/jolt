<?php

declare(encoding='UTF-8');
namespace jolt;
use \jolt\form_controller;

require_once('jolt/form_controller.php');

class form extends form_controller {

	private $exception = null;
	private $loader = null;
	private $validator = null;
	private $writer = null;

	public function __construct() {
		
	}

	public function __destruct() {
		$this->data = array();
	}

	public function attach_exception(\Exception $exception) {
		$this->exception = $exception;
		return $this;
	}

	public function attach_loader(\jolt\form\loader $loader) {
		$this->loader = $loader;
		return $this;
	}
	
	public function attach_writer(\jolt\form\writer $writer) {
		$this->writer = $writer;
		return $this;
	}

	public function attach_validator(\jolt\form\validator $validator) {
		$this->validator = $validator;
		return $this;
	}
	
	public function render_messages() {
		$single_message_template = '<li>%s</li>';
		$overall_message_template = '<div class="messages"><ul class="%s">%s</ul></div>';
		
		$html = null;
		$class = null;
		$messages = array();
		
		$message = $this->get_message();
		$errors = $this->get_errors();
		
		if (is_array($errors) && count($errors) > 0) {
			$messages = $errors;
			$class = 'error';
		} elseif (!empty($message)) {
			$messages = array($message);
			$class = 'general';
		}
		
		if (count($messages) > 0) {
			$html = array_reduce($messages, function($html, $message) use ($single_message_template) {
				return ($html .= sprintf($single_message_template, $message));
			});
			$html = sprintf($overall_message_template, $class, $html);
		}
		
		return $html;
	}

	public function load() {
		if (is_null($this->loader)) {
			return false;
		}

		$this->loader->set_id($this->get_id())
			->set_name($this->get_name());

		$loaded = $this->loader->load();
		if (!$loaded) {
			return false;
		}

		$this->set_data($this->loader->get_data())
			->set_errors($this->loader->get_errors());

		return true;
	}

	public function write() {
		if (is_null($this->writer)) {
			return false;
		}

		$this->writer->set_id($this->get_id())
			->set_name($this->get_name())
			->set_data($this->get_data())
			->set_errors($this->get_errors());

		$written = $this->writer->write();
		return $written;
	}

	public function validate() {
		if (is_null($this->validator)) {
			return false;
		}

		if ($this->validator->is_empty()) {
			return true;
		}

		$name = $this->get_name();
		$data = $this->get_data();

		$rule_set = $this->validator->get_rule_set();
		foreach ($rule_set as $field => $set) {
			$value = NULL;
			if (array_key_exists($field, $data)) {
				$value = $data[$field];
			}

			if (!$set->is_valid($value)) {
				$this->add_error($field, $set->get_error());
			}
		}

		if ($this->get_errors_count() > 0 ) {
			$error = $this->validator->get_error();
			if (empty($error)) {
				$error = 'The form '.$name.' failed to validate.';
			}

			$exception = $this->exception;
			if (!is_null($exception)) {
				throw new $exception($error, $this);
			} else {
				throw new \jolt\exception($error);
			}
		}

		return true;
	}
	
	public function set_response($response) {
		if (is_object($response)) {
			if ($response->has_errors()) {
				$this->set_errors($response->errors);
			}
		}
		
		return $this;
	}

	public function get_exception() {
		return $this->exception;
	}

}