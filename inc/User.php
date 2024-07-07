<?php
/**
 * User Class.
 *
 * This class handles the pinging of user events
 * to the Slack workspace.
 *
 * @package PingMySlack;
 */

namespace PingMySlack;

class User extends Service {
	/**
	 * Bind to WP.
	 *
	 * @since 1.0.0
	 *
	 * @return void
	 */
	public function register(): void {
		add_action( 'wp_login', [ $this, 'ping_on_user_login' ] );
		add_action( 'wp_logout', [ $this, 'ping_on_user_logout' ] );
	}

	/**
	 * Ping on User login.
	 *
	 * This method sends event logging to the Slack Workspace
	 * on user login.
	 *
	 * @param string   $user_login User Login.
	 * @param \WP_User $user       WP User.
	 *
	 * @since 1.0.0
	 *
	 * @return void
	 */
	public function ping_on_user_login( $user_login, $user ): void {
		$message = sprintf(
			"Ping: %s \n%s: %s, \n%s: %s",
			esc_html__( 'A User just logged in!', 'ping-my-slack' ),
			esc_html__( 'ID', 'ping-my-slack' ),
			esc_html( $user->ID ),
			esc_html__( 'User', 'ping-my-slack' ),
			esc_html( $user_login )
		);

		/**
		 * Filter Ping Message.
		 *
		 * Set custom Slack message to be sent when the
		 * user logs in.
		 *
		 * @param string   $message Slack Message.
		 * @param \WP_User $user    WP User.
		 *
		 * @since 1.0.0
		 *
		 * @return string
		 */
		$message = apply_filters( "ping_my_slack_login_message", $message, $user );

		$this->client->ping( $message );
	}
}
