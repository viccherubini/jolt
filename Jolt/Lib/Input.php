<?php

declare(encoding='UTF-8');
namespace Jolt;

function input_get_ipv4() {
	$ip = NULL;
	if ( true === isset($_SERVER) ) {
		if ( true === isset($_SERVER['HTTP_X_FORWARDED_FOR']) ) {
			$ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
		} elseif ( true === isset($_SERVER['HTTP_CLIENT_IP'])) {
			$ip = $_SERVER['HTTP_CLIENT_IP'];
		} else {
			$ip = er('REMOTE_ADDR', $_SERVER);
		}
	}
	
	return $ip;
}

function input_get_user_agent() {
	if ( true === exs('HTTP_USER_AGENT', $_SERVER) ) {
		return $_SERVER['HTTP_USER_AGENT'];
	}
	
	return NULL;
}

function input_clamp($val, $start, $end) {
	if ( $val < $start ) {
		$val = $start;
	} elseif ( $val > $end ) {
		$val = $end;
	}
	return $val;
}