<?php
/**
 * Kernel Interface.
 *
 * Define the base methods that must be shared
 * across concerte classes.
 *
 * @package PingMySlack
 */

namespace PingMySlack\Interfaces;

interface Kernel {
	/**
	 * Register call.
	 *
	 * @since 1.0.0
	 *
	 * @return void
	 */
	public function register(): void;
}
