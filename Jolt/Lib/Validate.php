<?php

declare(encoding='UTF-8');
namespace Jolt\Lib;

function validate_alphanum($v) {
	if ( true === empty($v) ) {
		return false;
	}

	$v = trim($v);
	if ( 1 === preg_match("/[^a-z0-9]/i", $v) ) {
		return false;
	}
	return true;
}

function validate_alpha($v) {
	if ( true === empty($s) ) {
		return false;
	}

	$s = trim($s);
	if ( 1 === preg_match("/[^a-z]/i", $s) ) {
		return false;
	}
	return true;
}

function validate_ascii($v) {
	if ( true === empty($v) ) {
		return false;
	}

	$clamp_low = ord(' ');
	$clamp_high = ord('~');

	$len = strlen($v);
	$is_ascii = true;
	for ( $i=0; $i<$len; $i++ ) {
		if ( ord($v[$i]) < $clamp_low || ord($v[$i]) > $clamp_high ) {
			$is_ascii = false;
		}
	}
	return $is_ascii;
}

function validate_between($v, $low, $high, $inclusive = true) {
	if ( true === empty($v) ) {
		return false;
	}

	if ( false === is_numeric($v) ) {
		$v = ord($v);
	}
	
	if ( false === is_numeric($low) ) {
		$low = ord($low);
	}
	
	if (false === is_numeric($high) ) {
		$high = ord($high);
	}

	if ( true === $inclusive ) {
		if ( $low <= $v && $high >= $v ) {
			return true;
		}
	} else {
		if ( $low < $v && $high > $v ) {
			return true;
		}
	}
	return false;
}

function validate_creditcard($v) {
	$number = preg_replace("/\D/", "", $v);

	if ( true === empty($number) ) {
		return false;
	}

	$num_length =  strlen($number);
	$double_number = false;
	$sum = 0;
	for ( $i = $num_length - 1; $i >= 0; $i-- ) {
		if ( true === $double_number ) {
			$sum += $number[$i] * 2;
			if ( 4 < $number[$i] ) {
				$sum -= 9;
			}
		} else {
			$sum += $number[$i];
		}
		$double_number = !$double_number;
	}
	return $sum % 10 == 0;
}