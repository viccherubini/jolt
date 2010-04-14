<?php

function array_exists_all($key_list, $array) {
	if ( false === is_array($key_list) ) {
		return false;
	}

	foreach ( $key_list as $key ) {
		if ( false === isset($array[$key]) ) {
			return false;
		}
	}
	
	return true;
}

function array_make_values_keys($a) {
	if ( 0 === count($a) ) {
		return array();
	}
	
	$during = array();
	foreach ( $a as $k => $v ) {
		$during[$v] = true;
	}
	return $during;
}

function array_keys_exist($keys, $array) {
	if ( false === lib_is_assoc($array) ) {
		return false;
	}
	
	$found = true;
	foreach ( $keys as $k ) {
		if ( false === array_key_exists($k, $array) ) {
			$found = false;
			break;
		}
	}
	
	return $found;
}

function array_slice_keys($keys, $hash) {
	if ( false === is_array($keys) ) {
		return array();
	}
	
	$final = array();
	$len = count($keys);
	for ( $i=0; $i<$len; $i++ ) {
		if ( true === isset($keys[$i], $hash) ) {
			$final[$keys[$i]] = $hash[$keys[$i]];
		} else {
			$final[$keys[$i]] = NULL;
		}
	}
	return $final;
}

function array_empty($a) {
	$empty = true;
	if ( true === is_array($a) ) {
		foreach ( $a as $i => $k ) {
			if ( false === empty($k) ) {
				$empty = false;
			}
		}
	}
	return $empty;
}