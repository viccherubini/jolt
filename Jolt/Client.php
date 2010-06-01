<?php

declare(encoding='UTF-8');
namespace Jolt;

/**
 * The client is what is responsible for sending rendered views, errors,
 * and/or headers back to the actual client. It should generally be initiated
 * by a Jolt_Dispatcher and not directly.
 * 
 * @author vmc <vmc@leftnode.com>
 */
class Client {

	private $status;
	private $header_list = array();
	private $content = NULL;
	
	public function __construct() {
		$this->status = 200;
		$this->header_list = array();
	}

	public function __destruct() {
		
	}
	
	
	
}