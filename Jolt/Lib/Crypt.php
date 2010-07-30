<?php

declare(encoding='UTF-8');
namespace Jolt\Lib;

function crypt_encrypt_string($key, $string) {
	$key = md5($key);
	$iv_size = mcrypt_get_iv_size(MCRYPT_RIJNDAEL_256, MCRYPT_MODE_ECB);
	$iv = mcrypt_create_iv($iv_size, MCRYPT_RAND);
	$encrypted_string = mcrypt_encrypt(MCRYPT_RIJNDAEL_256, $key, $string, MCRYPT_MODE_ECB, $iv);
	$encrypted_string = base64_encode($encrypted_string);

	return $encrypted_string;
}

function crypt_decrypt_string($key, $string) {
	$encrypted_string = base64_decode($string);
	$key = md5($key);

	$iv_size = mcrypt_get_iv_size(MCRYPT_RIJNDAEL_256, MCRYPT_MODE_ECB);
	$iv = mcrypt_create_iv($iv_size, MCRYPT_RAND);
	$decrypted_string = mcrypt_decrypt(MCRYPT_RIJNDAEL_256, $key, $encrypted_string, MCRYPT_MODE_ECB, $iv);
	$decrypted_string = trim($decrypted_string);

	return $decrypted_string;
}

function crypt_compute_hash($word, $salt) {
	$initial_salt = sha1($word);
	$hash = sha1($initial_salt . $word . $salt);

	return $hash;
}

function crypt_create_salt() {
	$salt = sha1(uniqid('', true));
	return $salt;
}