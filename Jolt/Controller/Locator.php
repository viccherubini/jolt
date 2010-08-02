<?php

declare(encoding='UTF-8');
namespace Jolt\Controller;

class Locator {
	
	public static $ext = '.php';
	public static $file = NULL;
	public static $dir = NULL;
	
	public static function load($directory, $controller) {
		$bits = explode('\\', $controller);
		$bitsLen = count($bits);
		
		self::$file = $bits[$bitsLen-1];
		self::$dir = $directory;
		
		if ( 0 === preg_match('/\\' . self::$ext . '$/i', $controller) ) {
			self::$file .= self::$ext;
		}
		
		$dirLength = strlen($directory);
		if ( $directory[$dirLength-1] !== DIRECTORY_SEPARATOR ) {
			self::$dir .= DIRECTORY_SEPARATOR;
		}
		
		$controllerPath = self::$dir . self::$file;
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

}