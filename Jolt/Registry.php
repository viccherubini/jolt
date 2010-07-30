<?php

declare(encoding='UTF-8');
namespace Jolt;

require_once 'Jolt/Lib/Library.php';

/**
 * A centralized registry to store global data. Although not as efficient
 * as using the 'global' keyword, it's much more elegant, and allows for
 * greater functionality by not allowing elements to be overwritten if they
 * already exist in the registry.
 * 
 * @author vmc <vmc@leftnode.com>
 */
class Registry {
	
	private static $registry = array();
	
	private static $overwrite_list = array();

	public static function reset() {
		self::$registry = array();
		return true;
	}

	public static function push($name, $item, $overwrite=true) {
		$exists = \Jolt\Lib\exs($name, self::$registry);
		
		if ( (!$exists) || ($exists && \Jolt\Lib\exs($name, self::$overwrite_list)) ) {
			self::$registry[$name] = $item;
			
			if ( true === $overwrite ) {
				self::$overwrite_list[$name] = true;
			}
		}
		return true;
	}
	
	public static function pop($name, $delete=false) {
		$item = \Jolt\Lib\er($name, self::$registry);
		if ( $delete ) {
			unset(self::$registry[$name]);
			
			if ( \Jolt\Lib\exs($name, self::$overwrite_list) ) {
				unset(self::$overwrite_list[$name]);
			}
		}
		return $item;
	}
}