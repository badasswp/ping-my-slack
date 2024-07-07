<?php
/**
 * Comment Class.
 *
 * This class is responsible for pinging comment events
 * to the Slack workspace.
 *
 * @package PingMySlack
 */

namespace PingMySlack;

class Comment extends Service {
	/**
	 * Bind to WP.
	 *
	 * @since 1.0.0
	 *
	 * @return void
	 */
	public function register(): void {
		add_action( 'transition_comment_status', [ $this, 'ping_on_comment_status_change' ], 10, 3 );
	}

	/**
	 * Ping on Comment Status change.
	 *
	 * Send notification to Slack channel when a
	 * Comment status changes.
	 *
	 * @since 1.0.0
	 *
	 * @param string      $new_status New Status.
	 * @param string      $old_status Old Status.
	 * @param \WP_Comment $comment    WP Comment.
	 *
	 * @return void
	 */
	public function ping_on_comment_status_change( $new_status, $old_status, $comment ): void {
		// Get comment.
		$this->comment = $comment;

		// Bail out, if not changed.
		if ( $old_status === $new_status ) {
			return;
		}

		switch ( $new_status ) {
			case 'hold':
				$message = $this->get_message( 'A Comment draft was just created!' );
				break;

			case 'approve':
				$message = $this->get_message( 'A Comment was just published!' );
				break;

			case 'trash':
				$message = $this->get_message( 'A Comment was just trashed!' );
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
			"Ping: %s \n%s: %s \n%s: %s \n%s: %s \n%s: %s",
			esc_html__( $message, 'ping-my-slack' ),
			esc_html__( 'Comment', 'ping-my-slack' ),
			esc_html( $this->comment->comment_content ),
			esc_html__( 'User', 'ping-my-slack' ),
			esc_html( $this->comment->comment_author_email ),
			esc_html__( 'Post', 'ping-my-slack' ),
			esc_html( get_the_title( $this->comment->comment_post_ID ) ),
			esc_html__( 'Date', 'ping-my-slack' ),
			esc_html( gmdate( 'H:i:s, d-m-Y' ) )
		);

		/**
		 * Filter Ping Message.
		 *
		 * Set custom Slack message to be sent when the
		 * user hits the publish button.
		 *
		 * @since 1.0.0
		 *
		 * @param string      $message Slack Message.
		 * @param \WP_Comment $comment WP Comment.
		 *
		 * @return string
		 */
		return apply_filters( 'ping_my_slack_comment_message', $message, $this->comment );
	}
}
