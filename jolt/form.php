<?php

declare(encoding='UTF-8');
namespace Jolt;
use \Jolt\FormController;

require_once 'Jolt/form_controller.php';

class Form extends FormController {

	private $exception = NULL;
	private $loader = NULL;
	private $validator = NULL;
	private $writer = NULL;

	public function __construct() {

	}

	public function __destruct() {
		$this->data = array();
	}

	public function attachException(\Exception $exception) {
		$this->exception = $exception;
		return $this;
	}

	public function attachLoader(\Jolt\Form\Loader $loader) {
		$this->loader = $loader;
		return $this;
	}

	public function attachWriter(\Jolt\Form\Writer $writer) {
		$this->writer = $writer;
		return $this;
	}

	public function attachValidator(\Jolt\Form\Validator $validator) {
		$this->validator = $validator;
		return $this;
	}

	public function load() {
		$loader = $this->loader;
		if ( is_null($loader) ) {
			return false;
		}

		$id = $this->getId();
		$name = $this->getName();

		$loader->setId($id)
			->setName($name);

		$loaded = $loader->load();
		if ( !$loaded ) {
			return false;
		}

		$data = $loader->getData();
		$errors = $loader->getErrors();

		$this->setData($data)
			->setErrors($errors);

		return true;
	}

	public function write() {
		$writer = $this->writer;
		if ( is_null($writer) ) {
			return false;
		}

		$id = $this->getId();
		$name = $this->getName();
		$data = $this->getData();
		$errors = $this->getErrors();

		$writer->setId($id)
			->setName($name)
			->setData($data)
			->setErrors($errors);

		$written = $writer->write();

		return $written;
	}

	public function validate() {
		$validator = $this->validator;
		if ( is_null($validator) ) {
			return false;
		}

		if ( $validator->isEmpty() ) {
			return true;
		}

		$formName = $this->getName();
		$data = $this->getData();

		$ruleSets = $validator->getRuleSet();
		foreach ( $ruleSets as $field => $ruleSet ) {
			$value = NULL;
			if ( array_key_exists($field, $data) ) {
				$value = $data[$field];
			}

			if ( !$ruleSet->isValid($value) ) {
				$error = $ruleSet->getError();
				$this->addError($field, $error);
			}
		}

		if ( $this->getErrorsCount() > 0 ) {
			$error = $validator->getError();
			if ( empty($error) ) {
				$error = 'The form ' . $formName . ' failed to validate.';
			}

			$exception = $this->exception;
			if ( !is_null($exception) ) {
				throw new $exception($error);
			} else {
				throw new \Jolt\Exception($error);
			}
		}

		return true;
	}

	public function getException() {
		return $this->exception;
	}

}
