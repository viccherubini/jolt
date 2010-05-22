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
 * @author vmc <vmc@joltcore.org>
 */
class Registry {
	
	/// The list of elements in the registry.
	private static $registry = array();
	
	/// Store a list of variables that are allowed to be overwritten.
	private static $overwrite_list = array();

	/**
	 * Resets the entire registry. Clears out all elements.
	 * 
	 * @retval bool Returns true.
	 */
	public static function reset() {
		self::$registry = array();
		return true;
	}

	/**
	 * Push an item onto the registry. By default, overwrite the item if it
	 * already exists.
	 * 
	 * @param $name      The name of the item to push. Should be easy to remember and short.
	 * @param $item      The actual item to push onto the registry.
	 * @param $overwrite Whether or no this item can be overwritten. If true,
	 *                   it can be overwritten, otherwise, it will never be.
	 * 
	 * @retval bool Returns true.
	 */
	public static function push($name, $item, $overwrite=true) {
		$exists = exs($name, self::$registry);
		
		if ( (false === $exists) || (true === $exists && true === exs($name, self::$overwrite_list)) ) {
			self::$registry[$name] = $item;
			
			if ( true === $overwrite ) {
				self::$overwrite_list[$name] = true;
			}
		}
		return true;
	}
	
	/**
	 * Retreive an element from the registry. Can optionally choose to delete it permanently.
	 * 
	 * @param $name   The name of the element to retreive. Same as the name in push().
	 * @param $delete Whether to delete the item or not. False by default.
	 *                If true, the item will be deleted. If the element previously disallowed
	 *                overwrites, the element will now allow it.
	 * 
	 * @retval mixed Returns the item if found, NULL otherwise.
	 */
	public static function pop($name, $delete=false) {
		$item = er($name, self::$registry);
		if ( true === $delete ) {
			unset(self::$registry[$name]);
			
			if ( true === exs($name, self::$overwrite_list) ) {
				unset(self::$overwrite_list[$name]);
			}
		}
		return $item;
	}
}