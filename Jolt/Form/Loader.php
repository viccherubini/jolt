<?php

declare(encoding='UTF-8');
namespace Jolt\Form;

abstract class Loader {

	private $id = NULL;
	private $name = NULL;

	public function __construct() {

	}

	public function __destruct() {

	}

	public function setId($id) {
		$this->id = trim($id);
		return $this;
	}

	public function setName($name) {
		$this->name = trim($name);
		return $this;
	}

	public function getId() {
		return $this->id;
	}

	public function getName() {
		return $this->name;
	}

	abstract public function load();
}