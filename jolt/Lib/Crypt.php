<?php

declare(encoding='UTF-8');
namespace Jolt\Lib;

function crypt_encrypt_string($key, $string) {
	$key = md5($key);
	$ivSize = mcrypt_get_iv_size(MCRYPT_RIJNDAEL_256, MCRYPT_MODE_ECB);
	$iv = mcrypt_create_iv($ivSize, MCRYPT_RAND);
	$encryptedString = mcrypt_encrypt(MCRYPT_RIJNDAEL_256, $key, $string, MCRYPT_MODE_ECB, $iv);
	$encryptedString = base64_encode($encryptedString);

	return $encryptedString;
}

function crypt_decrypt_string($key, $string) {
	$encryptedString = base64_decode($string);
	$key = md5($key);

	$ivSize = mcrypt_get_iv_size(MCRYPT_RIJNDAEL_256, MCRYPT_MODE_ECB);
	$iv = mcrypt_create_iv($ivSize, MCRYPT_RAND);
	$decryptedString = mcrypt_decrypt(MCRYPT_RIJNDAEL_256, $key, $encryptedString, MCRYPT_MODE_ECB, $iv);
	$decryptedString = trim($decryptedString);

	return $decryptedString;
}

function crypt_compute_hash($word, $salt) {
	$initialSalt = sha1($word);
	$hash = sha1($initialSalt . $word . $salt);

	return $hash;
}

function crypt_create_salt() {
	$salt = sha1(uniqid('', true));
	
	return $salt;
}