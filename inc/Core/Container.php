<?php
/**
 * Container Class.
 *
 * This class acts as a Factory container to load
 * all the services that the plugin uses.
 *
 * @package PingMeOnSlack
 */

namespace PingMeOnSlack\Core;

use PingMeOnSlack\Services\Post;
use PingMeOnSlack\Services\Boot;
use PingMeOnSlack\Services\Theme;
use PingMeOnSlack\Services\User;
use PingMeOnSlack\Services\Access;
use PingMeOnSlack\Services\Admin;
use PingMeOnSlack\Services\Comment;
use PingMeOnSlack\Services\Options;

final class Container {
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
			Access::class,
			Admin::class,
			Boot::class,
			Comment::class,
			Post::class,
			Theme::class,
			User::class,
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
