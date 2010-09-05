<?php

declare(encoding='UTF-8');
namespace Jolt;

class Exception extends \Exception {

	public function __construct($message=NULL) {
		$msg = NULL;
		
		$trace = $this->getTrace();
		$trace = current($trace);
		
		$filename = parent::getFile();
		$lineNumber = parent::getLine();
		
		if ( isset($trace['class']) ) {
			$msg = $trace['class'];
		}
		
		if ( isset($trace['type']) ) {
			$msg .= $trace['type'];
		}
		
		if ( isset($trace['function']) ) {
			$msg .= $trace['function'] . '()';
		}

		$msg .= " [{$message}] ({$filename} +{$lineNumber})";

		parent::__construct($msg);
	}

}