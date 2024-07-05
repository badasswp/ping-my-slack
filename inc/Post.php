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
	 * @since 1.0.0
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

	/**
	 * Get Message.
	 *
	 * This method returns the translated version
	 * of the Slack message.
	 *
	 * @since 1.0.0
	 *
	 * @param string $message Slack Message.
	 * @return string
	 */
	public function get_message( $message ): string {
		$message = sprintf(
			'%s %s: %s, %s: %s',
			esc_html__( $message, 'ping-my-slack' ),
			esc_html__( 'Post ID', 'ping-my-slack' ),
			esc_html( $this->post->ID ),
			esc_html__( 'Post Title', 'ping-my-slack' ),
			esc_html( $this->post->post_title )
		);

		/**
		 * Filter Ping Message.
		 *
		 * Set custom Slack message to be sent when the
		 * user hits the publish button.
		 *
		 * @since 1.0.0
		 *
		 * @return string
		 */
		return apply_filters( "ping_my_slack_${$this->post->post_type}_message", $message, $this->post );
	}
}
