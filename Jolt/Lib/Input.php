<?php

declare(encoding='UTF-8');
namespace Jolt\Lib;

function input_get_ipv4() {
	$ip = NULL;
	if ( isset($_SERVER) ) {
		if ( isset($_SERVER['HTTP_X_FORWARDED_FOR']) ) {
			$ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
		} elseif ( isset($_SERVER['HTTP_CLIENT_IP'])) {
			$ip = $_SERVER['HTTP_CLIENT_IP'];
		} elseif ( isset($_SERVER['REMOTE_ADDR']) ) {
			$ip = $_SERVER['REMOTE_ADDR'];
		}
	}
	
	return $ip;
}

function input_get_user_agent() {
	if ( isset($_SERVER['HTTP_USER_AGENT']) ) {
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