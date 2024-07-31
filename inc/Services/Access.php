<?php
/**
 * Access Class.
 *
 * This class handles the pinging of user login/logout
 * events to the Slack workspace.
 *
 * @package PingMySlack;
 */

namespace PingMySlack\Services;

use PingMySlack\Core\Client;
use PingMySlack\Abstracts\Service;
use PingMySlack\Interfaces\Kernel;

class Access extends Service implements Kernel {
	/**
	 * Bind to WP.
	 *
	 * @since 1.0.0
	 *
	 * @return void
	 */
	public function register(): void {
		add_action( 'wp_login', [ $this, 'ping_on_user_login' ], 10, 2 );
		add_action( 'wp_logout', [ $this, 'ping_on_user_logout' ] );
	}

	/**
	 * Ping on User login.
	 *
	 * This method sends event logging to the Slack Workspace
	 * on user login.
	 *
	 * @since 1.0.0
	 *
	 * @param string   $user_login User Login.
	 * @param \WP_User $user       WP User.
	 *
	 * @return void
	 */
	public function ping_on_user_login( $user_login, $user ): void {
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
		$this->client = apply_filters( 'ping_my_slack_login_client', $client = $this->client );

		$message = sprintf(
			"Ping: %s \n%s: %s \n%s: %s \n%s: %s",
			esc_html__( 'A User just logged in!', 'ping-my-slack' ),
			esc_html__( 'ID', 'ping-my-slack' ),
			esc_html( $user->ID ),
			esc_html__( 'User', 'ping-my-slack' ),
			esc_html( $user_login ),
			esc_html__( 'Date', 'ping-my-slack' ),
			esc_html( gmdate( 'H:i:s, d-m-Y' ) )
		);

		/**
		 * Filter Ping Message.
		 *
		 * Set custom Slack message to be sent when the
		 * user logs in.
		 *
		 * @since 1.0.0
		 *
		 * @param string   $message Slack Message.
		 * @param \WP_User $user    WP User.
		 *
		 * @return string
		 */
		$message = apply_filters( 'ping_my_slack_login_message', $message, $user );

		$this->client->ping( $message );
	}

	/**
	 * Ping on User logout.
	 *
	 * This method sends event logging to the Slack Workspace
	 * on user logout.
	 *
	 * @since 1.0.0
	 *
	 * @param int $user_id User ID.
	 * @return void
	 */
	public function ping_on_user_logout( $user_id ): void {
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
		$this->client = apply_filters( 'ping_my_slack_logout_client', $client = $this->client );

		$message = sprintf(
			"Ping: %s \n%s: %s \n%s: %s \n%s: %s",
			esc_html__( 'A User just logged out!', 'ping-my-slack' ),
			esc_html__( 'ID', 'ping-my-slack' ),
			esc_html( $user_id ),
			esc_html__( 'User', 'ping-my-slack' ),
			esc_html( get_user_by( 'id', $user_id )->user_login ),
			esc_html__( 'Date', 'ping-my-slack' ),
			esc_html( gmdate( 'H:i:s, d-m-Y' ) )
		);

		/**
		 * Filter Ping Message.
		 *
		 * Set custom Slack message to be sent when the
		 * user logs out.
		 *
		 * @since 1.0.0
		 *
		 * @param string   $message Slack Message.
		 * @param \WP_User $user    WP User.
		 *
		 * @return string
		 */
		$message = apply_filters( 'ping_my_slack_logout_message', $message, $user );

		$this->client->ping( $message );
	}
}
