<?php

declare(encoding='UTF-8');
namespace jolt;

class pdo extends \PDO {

	private $stmt = NULL;
	private $save_stmt = NULL;

	private $object = NULL;
	private $query_hash = NULL;

	public function __construct($dsn, $username=NULL, $password=NULL, $options=array()) {
		parent::__construct($dsn, $username, $password, $options);
		$this->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
	}

	// Helper functions
	public function id() {
		return $this->lastInsertId();
	}

	public function now($time=0, $short=false) {
		$time = (0 === $time ? time() : $time);
		$format = (false === $short ? 'Y-m-d H:i:s' : 'Y-m-d');
		$date = date($format, $time);

		return $date;
	}

	public function prep($query) {
		$this->stmt = $this->prepare($query);
		return $this;
	}

	// Searching methods
	public function find_all($object='stdClass', $parameters=array()) {
		if (!is_object($this->stmt)) {
			return array();
		}

		$this->bind_parameters($this->stmt, $parameters)
			->execute();

		return $this->stmt->fetchAll(\PDO::FETCH_CLASS, $object);
	}

	public function select($query, $parameters=array()) {
		$this->stmt = $this->prep($query)
			->bind_parameters($this->stmt, $parameters);
		$this->stmt->execute();
		return $this->stmt;
	}

	public function select_one($query, $parameters=array(), $object='stdClass') {
		return $this->select($query, $parameters)
			->fetchObject($object);
	}

	// Modification methods
	public function delete(\jolt\model $model) {
		if (!$model->is_saved()) {
			return false;
		}

		$table = get_class($model);
		$query = 'DELETE FROM '.$table.' WHERE id = :id';

		$modified = $this->modify($query, array('id' => $model->get_id()));
		return $modified;
	}

	public function modify($query, $parameters=array()) {
		$this->stmt = $this->prep($query)
			->bind_parameters($this->stmt, $parameters);
		$modified = $this->stmt->execute();
		return $modified;
	}

	public function save(\jolt\model $model) {
		$table_class = strtolower(get_class($model));
		$table_bits = explode('\\', $table_class);
		$table = end($table_bits);

		$is_insert = false;

		if (!$model->is_saved()) {
			if (isset($model->created)) {
				$model->set_created($this->now());
			}

			$values = $model->get_values();
			$members = array_keys($values);
			$members_string = implode(',', $members);

			$named_parameters = array_map(function($v) {
				return ':'.$v;
			}, $members);
			$named_parameters_string = implode(',', $named_parameters);

			$query = 'INSERT INTO '.$table.'('.$members_string.') VALUES ('.$named_parameters_string.')';
			$is_insert = true;
		} else {
			if (isset($model->updated)) {
				$model->set_updated($this->now());
			}

			$values = $model->get_values();

			if (isset($model->created)) {
				unset($values['created']);
			}

			$members = array_keys($values);
			$named_parameters = array_map(function($v) {
				return ($v.' = :'.$v);
			}, $members);
			$named_parameters_string = implode(',', $named_parameters);

			$query = 'UPDATE '.$table.' SET '.$named_parameters_string.' WHERE id = :pid';
			$values['pid'] = $values['id'];
		}

		$query_hash = sha1($query);
		if ($query_hash !== $this->query_hash) {
			$this->query_hash = $query_hash;
			$this->save_stmt = $this->prepare($query);
		}

		if (is_object($this->save_stmt)) {
			$this->save_stmt = $this->bind_parameters($this->save_stmt, $values);
			$this->save_stmt->execute();

			if ($is_insert) {
				$id = $this->id();
				$model->set_id($id);
			}
		}

		return $model;
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



	private function bind_parameters(\PDOStatement $stmt, array $parameters) {
		foreach ($parameters as $parameter => $value) {
			$type = \PDO::PARAM_STR;
			if (is_int($value)) {
				$type = \PDO::PARAM_INT;
			} elseif (is_bool($value)) {
				$type = \PDO::PARAM_BOOL;
			} elseif (is_null($value)) {
				$type = \PDO::PARAM_NULL;
			}
			$stmt->bindValue($parameter, $value, $type);
		}

		return $stmt;
	}

}