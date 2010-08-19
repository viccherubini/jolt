<?php

declare(encoding='UTF-8');
namespace Jolt\Lib;

function array_get($k, $a, $default=NULL) {
	if ( array_key_exists($k, $a) ) {
		$default = $a[$k];
	}
	
	return $default;
}

function array_keys_exist($keyList, $array) {
	if ( !is_array($keyList) ) {
		return false;
	}
	
	if ( !is_array($array) ) {
		return false;
	}

	$found = true;
	foreach ( $keyList as $key ) {
		if ( !array_key_exists($key, $array) ) {
			$found = false;
			break;
		}
	}
	
	return $found;
}

function array_empty($a) {
	if ( !is_array($a) ) {
		return true;
	}
	
	$empty = true;
	foreach ( $a as $i => $k ) {
		if ( !empty($k) ) {
			$empty = false;
		}
	}
	
	return $empty;
}