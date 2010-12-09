<?php

declare(encoding='UTF-8');
namespace Jolt;

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

		$controller = $this->controller;

		$responseCode = $controller->getResponseCode();
		$contentType = $controller->getContentType();

		$controllerHeaderList = $controller->getHeaderList();
		$headersList = headers_list();

		// Always remove the Content-Type header, let Jolt handle it
		header_remove('Content-Type');
		header('Content-Type: ' . $contentType, true, $responseCode);

		if ( defined('JOLT_VERSION') ) {
			header('X-Framework: Jolt ' . JOLT_VERSION);
		}

		// Go through the list of headers to send, if they exist in the
		// $controllerHeaderList, unset them
		foreach ( $headersList as $fullHeader ) {
			foreach ( $controllerHeaderList as $header => $value ) {
				if ( false !== stripos($fullHeader, $header) ) {
					header_remove($header);
				}

				$header = strtolower(trim($header));
				header($header . ':' . $value, true, $responseCode);

				// Special case for the Location header to prevent __toString()
				// from not outputting anything.
				if ( 'location' === $header ) {
					return '';
				}
			}
		}

		$renderedController = $this->controller
			->getRenderedController();

		return $renderedController;
	}

	public function getController() {
		return $this->controller;
	}
}