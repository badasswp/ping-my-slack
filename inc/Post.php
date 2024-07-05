<?php
/**
 * Post Class.
 *
 * This class binds all Post, Page, CPT logic
 * to the WP API.
 *
 * @package PingMySlack
 */

namespace PingMySlack;

class Post extends Service {
	/**
	 * Bind to WP.
	 *
	 * @since 1.0.0
	 *
	 * @return void
	 */
	public function register(): void {
		add_action( 'transition_post_status', [ $this, 'ping_on_post_creation' ], 10, 3 );
	}

	/**
	 * Ping on Post Creation.
	 *
	 * Send notification to Slack channel when a
	 * Post is created.
	 *
	 * @param string $new_status New Status.
	 * @param string $old_status Old Status.
	 * @param \WP_Post $post    WP Post.
	 *
	 * @return void
	 */
	public function ping_on_post_creation( $new_status, $old_status, $post ): void {
		// Get post.
		$this->post = $post;

		// Bail out, if not changed.
		if ( $old_status === $new_status || 'auto-draft' === $new_status ) {
			return;
		}

		switch( $new_status ) {
			case 'draft':
				$message = $this->get_message( 'A Post draft was just created!' );
				break;

			case 'publish':
				$message = $this->get_message( 'A Post was just published!' );
				break;

			case 'trash':
				$message = $this->get_message( 'A Post was just trashed!' );
				break;
		}

		$this->client->ping( $message );
	}
}
