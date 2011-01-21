<?php

declare(encoding='UTF-8');
namespace jolt;

/**
 * A centralized registry to store global data. Although not as efficient
 * as using the 'global' keyword, it's much more elegant, and allows for
 * greater functionality by not allowing elements to be overwritten if they
 * already exist in the registry.
 *
 * @author vmc <vmc@leftnode.com>
 */
class registry {

	private static $registry = array();
	private static $overwrite_list = array();

	public static function reset() {
		self::$registry = array();
		return true;
	}

	public static function push($name, $item, $overwrite=true) {
		$exists = array_key_exists($name, self::$registry);
		if ((!$exists) || ($exists && array_key_exists($name, self::$overwrite_list))) {
			self::$registry[$name] = $item;
			if ($overwrite) {
				self::$overwrite_list[$name] = true;
			}
		}
		return true;
	}

	public static function pop($name, $delete=false) {
		$item = NULL;
		if (array_key_exists($name, self::$registry)) {
			$item = self::$registry[$name];
		}
		
		if ($delete) {
			unset(self::$registry[$name]);
			if (array_key_exists($name, self::$overwrite_list)) {
				unset(self::$overwrite_list[$name]);
			}
		}
		return $item;
	}
	
}