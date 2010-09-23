<?php

declare(encoding='UTF-8');
namespace Jolt\Controller;

class Locator {
	
	private $file = NULL;
	private $path = NULL;
	
	const EXT = '.php';
	
	public function __construct() {
		
	}
	
	public function __destruct() {
		
	}
	
	public function load($path, $controller) {
		$bits = explode('\\', $controller);
		$bitsLen = count($bits);
		
		$this->file = strtolower($bits[$bitsLen-1]);
		$this->path = $path;
		
		if ( 0 === preg_match('/\\' . self::EXT . '$/i', $controller) ) {
			$this->file .= self::EXT;
		}
		
		$pathLength = strlen($path);
		if ( $path[$pathLength-1] !== DIRECTORY_SEPARATOR ) {
			$this->path .= DIRECTORY_SEPARATOR;
		}
		
		$controllerPath = $this->path . $this->file;
		if ( !is_file($controllerPath) ) {
			throw new \Jolt\Exception('controller_locator_path_not_found');
		}
		
		require_once $controllerPath;

		if ( !class_exists($controller) ) {
			throw new \Jolt\Exception('controller_locator_class_doesnt_exist');
		}
		
		$controllerObject = new $controller;
		
		if ( !($controllerObject instanceof \Jolt\Controller ) ) {
			throw new \Jolt\Exception('controller_locator_controller_not_instance_of_controller' . $controller);
		}
		
		return $controllerObject;
	}
	
	public function getPath() {
		return $this->path;
	}

	public function getFile() {
		return $this->file;
	}

}