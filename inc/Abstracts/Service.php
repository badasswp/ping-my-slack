<?php
/**
 * Service Abstraction.
 *
 * This defines the Service abstraction for
 * use by Plugin services.
 *
 * @package PingMeOnSlack
 */

namespace PingMeOnSlack\Abstracts;

use PingMeOnSlack\Core\Client;
use PingMeOnSlack\Interfaces\Kernel;

abstract class Service implements Kernel {
	/**
	 * Plugin Services.
	 *
	 * @var static[]
	 */
	public static $services = [];

	/**
	 * Slack Client.
	 *
	 * This client is responsible for sending messages
	 * to the Slack API.
	 *
	 * @since 1.0.0
	 *
	 * @var \PingMeOnSlack\Client
	 */
	public Client $client;

	/**
	 * Constructor.
	 *
	 * @since 1.0.0
	 */
	public function __construct() {
		$this->client = new Client();
	}

	/**
	 * Get Instance.
	 *
	 * This method gets a single Instance for each
	 * Plugin service.
	 *
	 * @since 1.0.0
	 *
	 * @return static
	 */
	public static function get_instance() {
		$service = get_called_class();

		if ( ! isset( static::$services[ $service ] ) ) {
			static::$services[ $service ] = new static();
		}

		return static::$services[ $service ];
	}

	/**
	 * Get Date.
	 *
	 * Utility function to obtain the current
	 * date to be logged.
	 *
	 * @since 1.0.0
	 *
	 * @return string
	 */
	public function get_date(): string {
		return gmdate( 'H:i:s, d-m-Y' );
	}

	/**
	 * Register Service.
	 *
	 * This method registers the Services' logic
	 * for plugin use.
	 *
	 * @since 1.0.0
	 *
	 * @return void
	 */
	abstract public function register(): void;
}
