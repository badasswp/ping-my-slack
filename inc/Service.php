<?php
/**
 * Service Abstraction.
 *
 * This defines the Service abstraction for
 * use by Plugin services.
 *
 * @package PingMySlack
 */

namespace PingMySlack;

abstract class Service {
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
	 * @var \PingMySlack\Client
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
