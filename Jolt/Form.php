<?php

declare(encoding='UTF-8');
namespace Jolt;
use \Jolt\FormController;

require_once 'Jolt/FormController.php';

class Form extends FormController {

	private $exception = NULL;
	private $loader = NULL;
	private $validator = NULL;
	private $writer = NULL;

	private $messages = array();

	public function __construct() {

	}

	public function __destruct() {
		$this->data = array();
	}

	public function addMessage($field, $message) {
		$this->messages[$field] = $message;
		return $this;
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

		// Loader requires the ID and name of the form
		$id = $this->getId();
		$name = $this->getName();

		$loader->setId($id)
			->setName($name);

		$loaded = $loader->load();
		if ( !$loaded ) {
			return false;
		}

		$dataKey = $loader->getDataKey();
		$data = $loader->getData();

		$this->setDataKey($dataKey)
			->setData($data);

		return true;
	}

	public function write() {
		$writer = $this->writer;
		if ( is_null($writer) ) {
			return false;
		}

		$id = $this->getId();
		$name = $this->getName();
		$dataKey = $this->getDataKey();
		$data = $this->getData();

		$writer->setId($id)
			->setName($name)
			->setDataKey($dataKey)
			->setData($data);

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

		$dataSet = $this->getDataSet();

		$dataSetCount = $this->getDataSetCount();
		$validatorCount = $validator->count();

		if ( $dataSetCount > $validatorCount ) {
			$this->throwException('there are more data fields than Validator RuleSets');
		}

		return true;
	}

	public function message($field) {
		if ( array_key_exists($field, $this->messages) ) {
			return $this->messages[$field];
		}
		return NULL;
	}


	public function getException() {
		return $this->exception;
	}

	public function getDataSet() {
		$dataKey = $this->getDataKey();
		$data = $this->getData();
		if ( !empty($dataKey) && array_key_exists($dataKey, $data) ) {
			return $data[$dataKey];
		}
		return $data;
	}

	public function getDataSetCount() {
		return count($this->getDataSet());
	}


	private function throwException($message) {
		$exception = $this->exception;
		if ( !is_null($exception) ) {
			throw new $exception($message);
		}
		throw new \Jolt\Exception($message);
	}

}