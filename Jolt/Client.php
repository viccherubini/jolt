<?php

declare(encoding='UTF-8');
namespace Jolt;

/**
 * The client is what is responsible for sending rendered views, errors,
 * and/or headers back to the actual client.
 * 
 * @author vmc <vmc@leftnode.com>
 */
class Client {

	private $controller;
	private $headersToSend = array();
	
	public function __construct() {
		
	}

	public function __destruct() {
		
	}
	
	public function __toString() {
		return $this->buildOutput();
	}
	
	public function attachController(\Jolt\Controller $controller) {
		$this->controller = clone $controller;
		return $this;
	}
	
	public function buildOutput() {
		// Must have controller
		if ( is_null($this->controller) ) {
			return '';
		}
		
		$headersToSend = $this->headersToSend;
		$headerList = $this->controller->getHeaderList();

		foreach ( $headerList as $header => $value ) {
			$sendHeader = true;
			foreach ( $headersToSend as $headerToSend ) {
				if ( false !== strpos($headerToSend, $header) ) {
					$sendHeader = false;
				}
			}
			
			if ( true === $sendHeader ) {
				$this->sendHeader($header, $value);
			}
		}
		
		return $this->controller->getRenderedController();
	}
	
	public function sendHeader($header, $value) {
		\header("{$header}: {$value}");
		return true;
	}
	
	public function setHeadersToSend(array $headersToSend) {
		$this->headersToSend = (array)$headersToSend;
		return $this;
	}
	
	public function getController() {
		return $this->controller;
	}
}