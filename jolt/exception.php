<?php namespace jolt;
declare(encoding='UTF-8');

require_once('jolt/redirect_exception.php');

class exception extends \Exception {

	public function __construct($message=null) {
		$msg = null;

		$trace = $this->getTrace();
		$trace = current($trace);

		$filename = parent::getFile();
		$line_number = parent::getLine();

		if (isset($trace['class'])) {
			$msg = $trace['class'];
		}

		if (isset($trace['type'])) {
			$msg .= $trace['type'];
		}

		if (isset($trace['function'])) {
			$msg .= $trace['function'] . '()';
		}

		$msg .= ' ['.$message.'] ('.$filename.' +'.$line_number.')';

		parent::__construct($msg);
	}

}