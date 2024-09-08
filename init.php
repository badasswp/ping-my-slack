<?php

namespace badasswp\PingMeOnSlack;

define( 'PING_ME_ON_SLACK', __DIR__ . '/vendor/autoload.php' );

/**
 * Run Notice, if Composer is not installed.
 *
 * @since 1.0.0
 *
 * @return void
 */
function ping_me_on_slack_notice(): void {
	echo esc_html__( 'Error: Composer is not installed!', 'ping-me-on-slack' );
}

/**
 * Run Plugin.
 *
 * @since 1.0.0
 *
 * @param string $autoload Composer Autoload file.
 * @return void
 */
function ping_me_on_slack_run( $autoload ): void {
	require_once $autoload;
	( \PingMeOnSlack\Plugin::get_instance() )->run();
}
