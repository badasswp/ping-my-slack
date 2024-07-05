<?php
/**
 * Post Class.
 *
 * This class binds all Post, Page, CPT logic
 * to the WP API.
 *
 * @package PingMySlack
 */

class Post extends Service {
	/**
	 * Bind to WP.
	 *
	 * @since 1.0.0
	 *
	 * @return void
	 */
	public function register(): void {
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

		$this->client->ping( $message );
	}
}
