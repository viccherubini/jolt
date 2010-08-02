<?php

declare(encoding='UTF-8');
namespace Jolt\Controller;

class Locator {
	
	public static $ext = '.php';
	public static $file = NULL;
	public static $dir = NULL;
	
	public static function load($directory, $controller) {
		self::$file = $controller;
		self::$dir = $directory;
		
		if ( 0 === preg_match('/\\' . self::$ext . '$/i', $controller) ) {
			self::$file .= self::$ext;
		}
		
		$dirLength = strlen($directory);
		if ( $directory[$dirLength-1] !== DIRECTORY_SEPARATOR ) {
			self::$dir .= DIRECTORY_SEPARATOR;
		}
		
		$path = self::$dir . self::$file;
		if ( is_file($path) ) {
			//throw new \Jolt\Exception('controller_locator_controller_not_found');
		}
		
		require_once $controllerPath;
		
		if ( !($controller instanceof \Jolt\Controller ) ) {
			throw new \Jolt\Exception('controller_locator_controller_not_instance_of_controller');
		}
		
		$controllerObject = new $controller();
		return $controllerObject;
	}

}