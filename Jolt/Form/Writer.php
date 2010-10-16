<?php

declare(encoding='UTF-8');
namespace Jolt\Form;

abstract class Writer {
	
	private $id = NULL;
	private $dataKey = NULL;
	private $name = NULL;

	private $data = array();
	
	public function __construct() {
		
	}
	
	public function __destruct() {
		$this->reset();
	}
	
	public function reset() {
		$this->data = array();
		$this->dataKey = NULL;
	}
	
	public function setId($id) {
		$this->id = trim($id);
		return $this;
	}
	
	public function setName($name) {
		$this->name = trim($name);
		return $this;
	}
	
	public function setData(array $data) {
		$this->data = $data;
		return $this;
	}
	
	public function setDataKey($dataKey) {
		$this->dataKey = trim($dataKey);
		return $this;
	}
	
	public function getId() {
		return $this->id;
	}
	
	public function getName() {
		return $this->name;
	}
	
	public function getData() {
		return $this->data;
	}
	
	public function getDataKey() {
		return $this->dataKey;
	}
	
	abstract public function write();
}