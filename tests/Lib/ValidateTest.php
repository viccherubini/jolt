<?php

declare(encoding='UTF-8');
namespace JoltTest\Lib;

use \Jolt\Lib,
	\JoltTest\TestCase;

class ValidateTest extends TestCase {

	public function testValidateAlphanum_MustHaveValue() {
		$valid = \Jolt\Lib\validate_alphanum(NULL);
		$this->assertFalse($valid);
	}
	
	/**
	 * @dataProvider providerAlphanumericValue
	 */
	public function testValidateAlphanum_HasAlphanumericValue($value) {
		$valid = \Jolt\Lib\validate_alphanum($value);
		$this->assertTrue($valid);
	}
	
	public function testValidateAlphanum_CanNotHaveSpaces() {
		$valid = \Jolt\Lib\validate_alphanum('test 123');
		$this->assertFalse($valid);
	}
	
	public function testValidateAlpha_MustHaveValue() {
		$valid = \Jolt\Lib\validate_alpha(NULL);
		$this->assertFalse($valid);
	}
	
	/**
	 * @dataProvider providerAlphaValue
	 */
	public function testValidateAlpha_HasAlphaValue($value) {
		$valid = \Jolt\Lib\validate_alpha($value);
		$this->assertTrue($valid);
	}
	
	public function testValidateAlpha_CanOnlyBeAlphaValue() {
		$valid = \Jolt\Lib\validate_alpha(sha1(uniqid()));
		$this->assertFalse($valid);
	}
	
	public function testValidateCreditcard_MustHaveValue() {
		$valid = \Jolt\Lib\validate_creditcard(NULL);
		$this->assertFalse($valid);
	}
	
	/**
	 * @dataProvider providerValidCreditCard
	 */
	public function testValidateCreditcard_IsValid($cc) {
		$valid = \Jolt\Lib\validate_creditcard($cc);
		$this->assertTrue($valid);
	}
	
	/**
	 * @dataProvider providerInvalidCreditCard
	 */
	public function testValidateCreditcard_IsInValid($cc) {
		$valid = \Jolt\Lib\validate_creditcard($cc);
		$this->assertFalse($valid);
	}
	
	public function providerAlphanumericValue() {
		return array(
			array(sha1(uniqid())),
			array('abc'),
			array('def10'),
			array('DEF10'),
			array(strtoupper(sha1(uniqid())))
		);
	}
	
	public function providerAlphaValue() {
		return array(
			array('abc'),
			array('def'),
			array('defadsfwqerasdgfasdf')
		);
	}
	
	public function providerValidCreditCard() {
		return array(
			array('378282246310005'),
			array('371449635398431'),
			array('378734493671000'),
			array('5610591081018250'),
			array('30569309025904'),
			array('38520000023237'),
			array('6011111111111117'),
			array('6011000990139424'),
			array('3530111333300000'),
			array('3566002020360505'),
			array('5555555555554444'),
			array('5105105105105100'),
			array('4111111111111111'),
			array('4012888888881881'),
			array('4222222222222'),
			array('5019717010103742'),
			array('6331101999990016'),
			array('5157168181838769'),
			array('5325476848283569'),
			array('5358930758046721'),
			array('552206034491 1641'),
			array('5441732109396857'),
			array('5279773212338813'),
			array('5227390610540222'),
			array('5233100522766647'),
			array('54 60076182695850'),
			array('5289294412088569'),
			array('4485981250745566'),
			array('4929352431999262'),
			array('4024007187784144'),
			array('4539885615068406'),
			array('4 916966300052823'),
			array('4539671180936197'),
			array('4532875861592594'),
			array('4532748956950664'),
			array('4539447 535021278'),
			array('4532209384280885'),
			array('4026348063372'),
			array('4532322230093'),
			array('4539619696100'),
			array('4762466788099'),
			array('4929820530414'),
			array('347777754389943'),
			array('372772668750817'),
			array('348552320006530'),
			array('344391128845532'),
			array('341 302797220538'),
			array('6011596941243363'),
			array('6011117749870156'),
			array('6011982045195891'),
			array('30001434056224'),
			array('30234340338630'),
			array('30373183226732'),
			array('201445481716052'),
			array('21 4984027279006'),
			array('201483310142596'),
			array('180014475049899'),
			array('180062863872216'),
			array('210032857730880'),
			array('3337249256532921'),
			array('3088241583441174'),
			array('3096576927276525'),
			array('8699165086 23789'),
			array('869912524254824'),
			array('869975802555865')
		);
	}
	
	public function providerInvalidCreditCard() {
		return array(
			array('1111'),
			array('222'),
			array('4405043202323'),
			array('3032')
		);
	}
}