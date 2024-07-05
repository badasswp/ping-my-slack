<?php
/**
 * Container Class.
 *
 * This class acts as a Factory container to load
 * all the services that the plugin uses.
 *
 * @package PingMySlack
 */

namespace PingMySlack;

class Container {
	/**
	 * Plugin Services
	 *
	 * @since 1.0.0
	 *
	 * @var mixed[]
	 */
	public static array $services;

	/**
	 * Constructor.
	 *
	 * @since 1.0.0
	 */
	public function __construct() {
		static::$services = [
			Post::class,
		];
	}

	/**
	 * Register Services.
	 *
	 * This method initialises the Services (singletons)
	 * for plugin use.
	 *
	 * @since 1.0.0
	 *
	 * @return void
	 */
	public function register(): void {
		foreach ( static::$services as $service ) {
			( $service::get_instance() )->register();
		}
	}
}
