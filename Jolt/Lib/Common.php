<?php

declare(encoding='UTF-8');
namespace Jolt\Lib;

function exs($k, $a) {
	if ( is_object($a) && property_exists($a, $k) ) {
		return true;
	}
	
	if ( is_array($a) && array_key_exists($k, $a) ) {
		return true;
	}
	
	return false;
}

function er($k, $a, $return = NULL) {
	if ( is_object($a) && property_exists($a, $k) ) {
		return $a->$k;
	}
	
	if ( is_array($a) && array_key_exists($k, $a) ) {
		return $a[$k];
	}
	
	return $return;
}

function lib_now($time = NULL) {
	if ( empty($time) ) {
		$time = time();
	}
	return date('Y-m-d H:i:s', $time);
}

function lib_peak_memory() {
	$oneMb = 1024*1024;
	$memory = memory_get_peak_usage() / $oneMb;
	$memory = round($memory, 4);
	return floatval($memory);
}