<?php
/**
 * Plugin Class.
 *
 * Set up the singleton instance, initialise
 * and run the plugin logic.
 *
 * @package PingMeOnSlack
 */

namespace PingMeOnSlack;

use PingMeOnSlack\Core\Container;

final class Plugin {
	/**
	 * Plugin instance.
	 *
	 * @since 1.0.0
	 *
	 * @var Plugin
	 */
	protected static $instance;

	/**
	 * Get Instance.
	 *
	 * Return singeleton instance for Plugin.
	 *
	 * @since 1.0.0
	 *
	 * @return Plugin
	 */
	public static function get_instance(): Plugin {
		if ( null === static::$instance ) {
			static::$instance = new self();
		}

		return static::$instance;
	}

	/**
	 * Bind to WP.
	 *
	 * @since 1.0.0
	 *
	 * @return void
	 */
	public function run(): void {
		( new Container() )->register();
	}
}
