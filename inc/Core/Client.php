<?php
/**
 * Client Class.
 *
 * This class handles sending Slack notifications
 * via API calls.
 *
 * @package PingMeOnSlack
 */

namespace PingMeOnSlack\Core;

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
	public SlackClient $slack;

	/**
	 * Slack Args.
	 *
	 * Specify JSON payload here to be sent when
	 * making API calls.
	 *
	 * @since 1.0.0
	 *
	 * @var mixed[]
	 */
	public array $args;

	/**
	 * Plugin Settings.
	 *
	 * Grab plugin options from Options table specific
	 * to this plugin.
	 *
	 * @since 1.0.0
	 *
	 * @var mixed[]
	 */
	public array $settings;

	/**
	 * Set up.
	 *
	 * @since 1.0.0
	 *
	 * @return void
	 */
	public function __construct() {
		$this->settings = get_option( 'ping_me_on_slack', [] );

		$this->args = [
			'channel'  => $this->settings['channel'] ?? '',
			'username' => $this->settings['username'] ?? '',
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
		$this->slack = new SlackClient(
			$this->settings['webhook'] ?? '',
			$this->args
		);

		try {
			$this->slack->send( $message );
		} catch ( \RuntimeException $e ) {
			error_log(
				sprintf(
					'Fatal Error: Something went wrong... %s',
					wp_json_encode( $this->args ) . ' ' . $e->getMessage()
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
			do_action( 'ping_me_on_slack_on_ping_error', $e );
		}
	}
}
