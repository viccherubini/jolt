<?php

declare(encoding='UTF-8');
namespace JoltTest\Lib;

use \Jolt\Lib,
	\JoltTest\TestCase;

class InputTest extends TestCase {

	public function testInputGetIpv4_ServerMustBeSet() {
		$ip = \Jolt\Lib\input_get_ipv4();
		$this->assertTrue(is_null($ip));
	}
	
	public function testInputGetIpv4_FromForwardedFor() {
		$ipAddress = $this->buildIpv4Address();
		$_SERVER['HTTP_X_FORWARDED_FOR'] = $ipAddress;
		
		$this->assertEquals($ipAddress, \Jolt\Lib\input_get_ipv4());
	}
	
	public function testInputGetIpv4_FromClientIp() {
		$ipAddress = $this->buildIpv4Address();
		$_SERVER['HTTP_CLIENT_IP'] = $ipAddress;
		
		$this->assertEquals($ipAddress, \Jolt\Lib\input_get_ipv4());
	}
	
	public function testInputGetIpv4_FromRemoteAddr() {
		$ipAddress = $this->buildIpv4Address();
		$_SERVER['REMOTE_ADDR'] = $ipAddress;
		
		$this->assertEquals($ipAddress, \Jolt\Lib\input_get_ipv4());
	}
	
	public function testInputGetUserAgent_ServerMustBeSet() {
		$userAgent = \Jolt\Lib\input_get_user_agent();
		$this->assertTrue(is_null($userAgent));
	}
	
	public function testInputGetUserAgent_FromHttpUserAgent() {
		$ua = 'Mozilla Firefox';
		$_SERVER['HTTP_USER_AGENT'] = $ua;
		
		$userAgent = \Jolt\Lib\input_get_user_agent();
		$this->assertEquals($ua, $userAgent);
	}
	
	public function testInputClamp_CanNotBeGreaterThanStart() {
		$start = 10;
		$end = 15;
		$value = 5;
		
		$this->assertEquals($start, \Jolt\Lib\input_clamp($value, $start, $end));
	}
	
	public function testInputClamp_CanNotBeGreatherThanEnd() {
		$start = 10;
		$end = 15;
		$value = 20;
		
		$this->assertEquals($end, \Jolt\Lib\input_clamp($value, $start, $end));
	}
	
	public function testInputClamp_BetweenStartAndEnd() {
		$start = 10;
		$end = 15;
		$value = 12;
		
		$this->assertEquals($value, \Jolt\Lib\input_clamp($value, $start, $end));
	}
	
	private function buildIpv4Address() {
		$array = array(mt_rand(1, 255), mt_rand(1, 255), mt_rand(1, 255), mt_rand(1, 255));
		return implode('.', $array);
	}
}