<?php
/**
 * Client Class.
 *
 * This class handles sending Slack notifications
 * via API calls.
 *
 * @package PingMySlack
 */

namespace PingMySlack\Core;

use Maknz\Slack\Client as SlackClient;

class Client {
	/**
	 * Slack Client.
	 *
	 * Responsible for sending JSON payload to Slack's
	 * services endpoint on behalf of plugin.
	 *
	 * @since 1.0.0
	 *
	 * @var SlackClient
	 */
	private SlackClient $slack;

	/**
	 * Slack Args.
	 *
	 * Specify JSON payload here to be sent when
	 * making API calls.
	 *
	 * @var mixed[]
	 */
	public array $args;

	/**
	 * Set up.
	 *
	 * @since 1.0.0
	 *
	 * @return void
	 */
	public function __construct() {
		$settings = get_option( 'ping_my_slack', [] );

		$this->args = [
			'channel'  => $settings['channel'] ?? '',
			'username' => $settings['username'] ?? '',
		];
	}

	/**
	 * Ping Slack.
	 *
	 * This method handles the Remote POST calls
	 * to Slack API endpoints.
	 *
	 * @since 1.0.0
	 *
	 * @param string $message Slack Message.
	 * @return void
	 */
	public function ping( $message ): void {
		try {
			$this->slack->send( $message );
		} catch ( \RuntimeException $e ) {
			error_log(
				sprintf(
					'Fatal Error: Something went wrong... %s',
					$e->getMessage()
				)
			);

			/**
			 * Fire after Exception is caught.
			 *
			 * This action provides a way to use the caught
			 * exception for logging purposes.
			 *
			 * @since 1.0.0
			 *
			 * @param \RuntimeException $e Exception object.
			 * @return void
			 */
			do_action( 'ping_my_slack_on_ping_error', $e );
		}
	}
}
