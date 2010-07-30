<?php

declare(encoding='UTF-8');
namespace Jolt\Lib;

function validate_alphanum($v) {
	if ( empty($v) ) {
		return false;
	}

	$v = trim($v);
	if ( 1 === preg_match("/[^a-z0-9]/i", $v) ) {
		return false;
	}
	return true;
}

function validate_alpha($v) {
	if ( empty($v) ) {
		return false;
	}

	$v = trim($v);
	if ( 1 === preg_match("/[^a-z]/i", $v) ) {
		return false;
	}
	return true;
}

function validate_creditcard($v) {
	$number = preg_replace("/\D/", "", $v);

	if ( empty($number) ) {
		return false;
	}

	$numLength = strlen($number);
	$doubleNumber = false;
	$sum = 0;
	for ( $i = $numLength - 1; $i >= 0; $i-- ) {
		if ( $doubleNumber ) {
			$sum += $number[$i] * 2;
			if ( 4 < $number[$i] ) {
				$sum -= 9;
			}
		} else {
			$sum += $number[$i];
		}
		$doubleNumber = !$doubleNumber;
	}
	return $sum % 10 == 0;
}