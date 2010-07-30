<?php

declare(encoding='UTF-8');
namespace JoltTest\Lib;

use \Jolt\Lib,
	\JoltTest\TestCase;

class CryptTest extends TestCase {

	public function testCryptEncryptString_CanEncryptDecryptString() {
		$key = mt_rand(1, 100);
		$string = sha1(uniqid());
		
		$encryptedString = \Jolt\Lib\crypt_encrypt_string($key, $string);
		$this->assertFalse(empty($encryptedString));
		
		$decryptedString = \Jolt\Lib\crypt_decrypt_string($key, $encryptedString);
		$this->assertEquals($string, $decryptedString);
	}
	
	public function testCryptComputeHash_ReturnsOverallHash() {
		$word = mt_rand(1, 100);
		$salt = \Jolt\Lib\crypt_create_salt();
		
		$hash = \Jolt\Lib\crypt_compute_hash($word, $salt);
		
		$this->assertFalse(empty($hash));
	}
}