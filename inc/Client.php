<?php
/**
 * Client class.
 *
 * This class handles sending Slack notifications
 * via API calls.
 *
 * @package PingMySlack
 */

namespace PingMySlack;

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
	private SlackClient $client;

	/**
	 * Set up.
	 *
	 * @since 1.0.0
	 *
	 * @return void
	 */
	public function __construct() {
		$settings = get_option( 'ping_my_slack', [] );

		$this->client = new SlackClient(
			$settings['webhook'] ?? '',
			[
				'channel'  => $settings['channel'] ?? '',
				'username' => $settings['username'] ?? '',
			]
		);
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
			$this->client->send( $message );
		} catch ( \RuntimeException $e ) {
			error_log(
				sprintf(
					'Fatal Error: Something went wrong... %s',
					$e->getMessage()
				)
			);
		}
	}
}
