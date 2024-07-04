<?php
/**
 * Plugin class.
 *
 * Set up the singleton instance, initialise
 * and run the plugin logic.
 *
 * @package PingMySlack
 */

namespace PingMySlack;

use Maknz\Slack\Client;

class Plugin {
	/**
	 * Plugin instance.
	 *
	 * @since 1.0.0
	 *
	 * @var Plugin
	 */
	protected static $instance;

	/**
	 * Set up.
	 *
	 * @since 1.0.0
	 *
	 * @return void
	 */
	public function __construct() {
		$this->client = new Client();
	}

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
		add_action( 'publish_post', [ $this, 'ping_on_post_creation' ], 10, 2 );
	}

	/**
	 * Ping on Post Creation.
	 *
	 * Send notification to Slack channel when a
	 * Post is created.
	 *
	 * @param int      $post_id Post ID.
	 * @param \WP_Post $post    WP Post.
	 *
	 * @return void
	 */
	public function ping_on_post_creation( $post_id, $post ): void {
		$message = sprintf(
			'A Post was just created! ID: %s, Post Title: %s',
			esc_html( $post_id ),
			esc_html( get_post_field( 'post_title', $post_id ) )
		);

		$client->withIcon( ':ghost' )->send( $message );
	}
}
