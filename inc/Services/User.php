<?php
/**
 * User Class.
 *
 * This class handles the pinging of user account creation,
 * modification & deletion.
 *
 * @package PingMySlack;
 */

namespace PingMySlack\Services;

use PingMySlack\Abstracts\Service;
use PingMySlack\Interfaces\Kernel;

class User extends Service implements Kernel {
	/**
	 * Bind to WP.
	 *
	 * @since 1.0.0
	 *
	 * @return void
	 */
	public function register(): void {
		add_action( 'user_register', [ $this, 'ping_on_user_creation' ], 10, 2 );
		add_action( 'wp_update_user', [ $this, 'ping_on_user_modification' ], 10, 3 );
		add_action( 'deleted_user', [ $this, 'ping_on_user_deletion' ], 10, 3 );
	}

	/**
	 * Ping on User Creation.
	 *
	 * This method sends event logging to the Slack Workspace
	 * on user creation.
	 *
	 * @since 1.0.0
	 *
	 * @param int     $user_id   User ID.
	 * @param mixed[] $user_data User Data.
	 *
	 * @return void
	 */
	public function ping_on_user_creation( $user_id, $user_data ): void {
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
		$this->client = apply_filters( 'ping_my_slack_user_creation_client', $client = $this->client );

		$message = sprintf(
			"Ping: %s \n%s: %s \n%s: %s \n%s: %s",
			esc_html__( 'A User was just created!', 'ping-my-slack' ),
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
		 * Set custom Slack message to be sent when a new
		 * user is created.
		 *
		 * @since 1.0.0
		 *
		 * @param string   $message Slack Message.
		 * @param int      $user_id User ID.
		 *
		 * @return string
		 */
		$message = apply_filters( 'ping_my_slack_user_creation_message', $message, $user_id );

		$this->client->ping( $message );
	}

	/**
	 * Ping on User Modification.
	 *
	 * This method sends event logging to the Slack Workspace
	 * on user modification/update.
	 *
	 * @since 1.0.0
	 *
	 * @param int   $user_id      The ID of the user that was just updated.
	 * @param array $userdata     The array of user data that was updated.
	 * @param array $userdata_raw The unedited array of user data that was updated.
	 */
	public function ping_on_user_modification( $user_id, $userdata, $userdata_raw ) {
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
		$this->client = apply_filters( 'ping_my_slack_user_modification_client', $client = $this->client );

		$message = sprintf(
			"Ping: %s \n%s: %s \n%s: %s \n%s: %s",
			esc_html__( 'A User was just modified!', 'ping-my-slack' ),
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
		 * Set custom Slack message to be sent when a WP
		 * user is modified or updated.
		 *
		 * @since 1.0.0
		 *
		 * @param string   $message Slack Message.
		 * @param int      $user_id User ID.
		 *
		 * @return string
		 */
		$message = apply_filters( 'ping_my_slack_user_modification_message', $message, $user_id );

		$this->client->ping( $message );
	}

	/*
	 * Ping on User Deletion.
	 *
	 * This method sends event logging to the Slack Workspace
	 * on user deletion.
	 *
	 * @since 1.0.0
	 *
	 * @param int      $user_id  ID of the deleted user.
	 * @param int|null $reassign ID of the user to reassign posts and links to.
	 *                           Default null, for no reassignment.
	 * @param WP_User  $user     WP_User object of the deleted user.
	 */
	public function ping_on_user_deletion( $user_id, $reassign, $user ) {
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
		$this->client = apply_filters( 'ping_my_slack_user_deletion_client', $client = $this->client );

		$message = sprintf(
			"Ping: %s \n%s: %s \n%s: %s \n%s: %s",
			esc_html__( 'A User was just deleted!', 'ping-my-slack' ),
			esc_html__( 'ID', 'ping-my-slack' ),
			esc_html( $user_id ),
			esc_html__( 'User', 'ping-my-slack' ),
			esc_html( $user->user_login ),
			esc_html__( 'Date', 'ping-my-slack' ),
			esc_html( gmdate( 'H:i:s, d-m-Y' ) )
		);

		/**
		 * Filter Ping Message.
		 *
		 * Set custom Slack message to be sent when a previous
		 * user is deleted.
		 *
		 * @since 1.0.0
		 *
		 * @param string   $message Slack Message.
		 * @param int      $user_id User ID.
		 *
		 * @return string
		 */
		$message = apply_filters( 'ping_my_slack_user_deletion_message', $message, $user_id );

		$this->client->ping( $message );
	}
}
