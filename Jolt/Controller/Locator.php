<?php

declare(encoding='UTF-8');
namespace Jolt\Controller;

class Locator {
	
	private $file = NULL;
	private $dir = NULL;
	
	const EXT = '.php';
	
	public function __construct() {
		
	}
	
	public function __destruct() {
		
	}
	
	public function load($directory, $controller) {
		$bits = explode('\\', $controller);
		$bitsLen = count($bits);
		
		$this->file = $bits[$bitsLen-1];
		$this->dir = $directory;
		
		if ( 0 === preg_match('/\\' . self::EXT . '$/i', $controller) ) {
			$this->file .= self::EXT;
		}
		
		$dirLength = strlen($directory);
		if ( $directory[$dirLength-1] !== DIRECTORY_SEPARATOR ) {
			$this->dir .= DIRECTORY_SEPARATOR;
		}
		
		$controllerPath = $this->dir . $this->file;
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
	
	public function getDir() {
		return $this->dir;
	}

	public function getFile() {
		return $this->file;
	}

}