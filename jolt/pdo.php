<?php

declare(encoding='UTF-8');
namespace jolt;

class pdo extends \PDO {

	private $stmt = NULL;
	private $object = NULL;

	public function __construct($dsn, $username=NULL, $password=NULL, $options=array()) {
		parent::__construct($dsn, $username, $password, $options);
		$this->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
	}

	public function id() {
		return $this->lastInsertId();
	}

	public function prep($query, $object='stdClass') {
		$this->stmt = $this->prepare($query);
		$this->object = $object;
		return $this;
	}

	public function find_all($parameters=array()) {
		if (!is_object($this->stmt)) {
			return array();
		}

		$this->bind_parameters($parameters)
			->execute();

		return $this->stmt->fetchAll(\PDO::FETCH_CLASS, $this->object);
	}

	public function modify($query, $parameters=array()) {
		return $this->prep($query)
			->bind_parameters($parameters)
			->execute();
	}

	public function select($query, $parameters=array()) {
		$this->prep($query)
			->bind_parameters($parameters)
			->execute();
		return $this->stmt;
	}

	public function select_one($query, $parameters=array(), $object='stdClass') {
		return $this->select($query, $parameters)
			->fetchObject($object);
	}

	public function stmt() {
		return $this->stmt;
	}

	public function table_exists($table_to_test) {
		$table_exists = false;
		$result_tables = $this->query('SHOW TABLES');
		while ($table_row = $result_tables->fetch()) {
			$table = trim(current($table_row));
			if ($table_to_test === $table) {
				$table_exists = true;
				break;
			}
		}

		return $table_exists;
	}



	private function bind_parameters(array $parameters) {
		if (!is_object($this->stmt)) {
			return false;
		}

		foreach ($parameters as $parameter => $value) {
			$type = \PDO::PARAM_STR;
			if (is_int($value)) {
				$type = \PDO::PARAM_INT;
			} elseif (is_bool($value)) {
				$type = \PDO::PARAM_BOOL;
			} elseif (is_null($value)) {
				$type = \PDO::PARAM_NULL;
			}
			$this->stmt->bindValue($parameter, $value, $type);
		}

		return $this->stmt;
	}

}