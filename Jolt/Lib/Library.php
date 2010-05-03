<?php

require_once 'Jolt/Lib/Array.php';
require_once 'Jolt/Lib/Crypt.php';
require_once 'Jolt/Lib/Db.php';
require_once 'Jolt/Lib/Input.php';
require_once 'Jolt/Lib/Validate.php';

function exs($k, $a) {
	if ( true === is_object($a) && true === isset($a->$k) ) {
		return true;
	}
	
	if ( true === is_array($a) && true === isset($a[$k]) ) {
		return true;
	}
	
	return false;
}

function er($k, $a, $return = NULL) {
	if ( true === is_object($a) && true === isset($a->$k) ) {
		return $a->$k;
	}
	
	if ( true === is_array($a) && true === isset($a[$k]) ) {
		return $a[$k];
	}
	
	return $return;
}

function lib_print_r($array, $return = false) {
	$str = '<pre>' . print_r($array, true) . '</pre>';
	if ( true === $return ) {
		return $str;
	} else {
		echo $str;
	}
	
	return true;
}

function lib_rename_controller($controller) {
	$controller = strtolower(trim($controller));
	$controller = preg_replace('/[^a-z_0-9]/i', NULL, $controller);
	$controller = str_replace('_', ' ', $controller);
	$controller = ucwords($controller);
	$controller = str_replace(' ', '_', $controller);
	return $controller;
}

function lib_rename_method($method) {
	$method = preg_replace('/[^a-z_0-9]/i', NULL, $method);
	return strtolower($method);
}

function lib_rename_view($view) {
	$view = preg_replace('/[^a-z_0-9\-]/i', NULL, $view);
	return strtolower($view);
}

function lib_is_assoc($array) {
	if ( false === is_array($array) ) {
		return false;
	}
	
	$is_assoc = true;
	$keys = array_keys($array);
	foreach ( $keys as $key ) {
		if ( false === is_string($key) ) {
			$is_assoc = false;
		}
	}
	
	return $is_assoc;
}

function lib_peak_memory() {
	$one_mb = 1024*1024;
	$memory = memory_get_peak_usage() / $one_mb;
	$memory = round($memory, 4);
	return $memory;
}

function lib_throw_if($value, $exception) {
	if ( true === $value ) {
		throw new Jolt_Exception($exception);
	}
	return true;
}

function lib_throw_if_not($value, $exception) {
	return lib_throw_if(false === $value, $exception);
}