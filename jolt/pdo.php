<?php

declare(encoding='UTF-8');
namespace jolt;

class pdo extends \PDO {

	private $stmt = NULL;

	public function __construct($dsn, $username=NULL, $password=NULL, $options=array()) {
		parent::__construct($dsn, $username, $password, $options);
		$this->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
	}

	public function id() {
		return $this->lastInsertId();
	}

	public function modify($query, $parameters=array()) {
		$this->bind($query, $parameters);
		$this->stmt->execute();
		return true;
	}

	public function select($query, $parameters=array()) {
		$this->bind($query, $parameters);
		$this->stmt->execute();
		return $this->stmt;
	}

	public function select_one($query, $parameters=array(), $object='stdClass') {
		$this->select($query, $parameters);
		return $this->stmt->fetchObject($object);
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

	private function bind($query, $parameters=array()) {
		$this->stmt = $this->prepare($query);
		foreach ($parameters as $parameter => $value) {
			$type = \PDO::PARAM_STR;
			if (is_int($value)) { $type = \PDO::PARAM_INT; }
			if (is_bool($value)) { $type = \PDO::PARAM_BOOL; }
			if (is_null($value)) { $type = \PDO::PARAM_NULL; }
			$this->stmt->bindValue($parameter, $value, $type);
		}

		return $this->stmt;
	}

}