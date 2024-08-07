<?php
/**
 * Post Class.
 *
 * This class binds all Post, Page, CPT logic
 * to the WP API.
 *
 * @package PingMySlack
 */

namespace PingMySlack\Services;

use PingMySlack\Core\Client;
use PingMySlack\Abstracts\Service;
use PingMySlack\Interfaces\Kernel;

class Post extends Service implements Kernel {
	/**
	 * Bind to WP.
	 *
	 * @since 1.0.0
	 *
	 * @return void
	 */
	public function register(): void {
		add_action( 'transition_post_status', [ $this, 'ping_on_post_status_change' ], 10, 3 );
	}

	/**
	 * Ping on Post Status change.
	 *
	 * Send notification to Slack channel when a
	 * Post status changes.
	 *
	 * @since 1.0.0
	 *
	 * @param string   $new_status New Status.
	 * @param string   $old_status Old Status.
	 * @param \WP_Post $post       WP Post.
	 *
	 * @return void
	 */
	public function ping_on_post_status_change( $new_status, $old_status, $post ): void {
		// Get Post.
		$this->post = $post;

		// Bail out, if not changed.
		if ( $old_status === $new_status || 'auto-draft' === $new_status ) {
			return;
		}

		// Get Event Type.
		$this->event = $new_status;

		switch ( $new_status ) {
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

		/**
		 * Filter Slack Client.
		 *
		 * Customise the Client instance here, you can
		 * make this extensible.
		 *
		 * @since 1.0.0
		 *
		 * @param Client $client Client Instance.
		 * @return Client
		 */
		$this->client = apply_filters( "ping_my_slack_{$this->post->post_type}_client", $client = $this->client );

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
			"%s: %s \n%s: %s \n%s: %s \n%s: %s \n%s: %s",
			esc_html__( 'Ping', 'ping-my-slack' ),
			esc_html__( $message, 'ping-my-slack' ),
			esc_html__( 'ID', 'ping-my-slack' ),
			esc_html( $this->post->ID ),
			esc_html__( 'Title', 'ping-my-slack' ),
			esc_html( $this->post->post_title ),
			esc_html__( 'User', 'ping-my-slack' ),
			esc_html( get_user_by( 'id', $this->post->post_author )->user_login ),
			esc_html__( 'Date', 'ping-my-slack' ),
			esc_html( $this->get_date() )
		);

		/**
		 * Filter Ping Message.
		 *
		 * Set custom Slack message to be sent when the
		 * user hits the publish button.
		 *
		 * @since 1.0.0
		 *
		 * @param string   $message Slack Message.
		 * @param \WP_Post $post    WP Post.
		 * @param string   $event   Event Type.
		 *
		 * @return string
		 */
		return (string) apply_filters( "ping_my_slack_{$this->post->post_type}_message", $message, $this->post, $this->event );
	}
}
