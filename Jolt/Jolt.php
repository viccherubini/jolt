<?php

declare(encoding='UTF-8');
namespace Jolt;

require_once 'Exception.php';

/**
 * This class is an entrance for building an application. It is entirely
 * static and gives you access to building new objects, starting the 
 * application, and basic CSRF protection.
 * 
 * @author vmc <vmc@leftnode.com>
 */
class Jolt {
	private static $router = NULL;
	private static $dispatcher = NULL;
	private static $client = NULL;
	
	private $config = array();
	
	public static function attachRouter(Router $router) {
		if ( 0 === $router->getRouteCount() ) {
			throw new \Jolt\Exception('jolt_router_empty');
		}
		
		self::$router = $router;
	}
	
	public static function attachDispatcher(Dispatcher $dispatcher) {
		self::$dispatcher = $dispatcher;
	}
	
	public static function attachClient(Client $client) {
		self::$client = $client;
	}
}
