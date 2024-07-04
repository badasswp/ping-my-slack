<?php

namespace badasswp\PingMySlack;

define ( 'PINGMYSLACK', __DIR__ . '/vendor/autoload.php' );

/**
 * Fire Notice, if Composer is not installed.
 *
 * @since 1.0.0
 *
 * @return void
 */
function ping_my_slack_notice(): void {
	printf(
		esc_html__( 'Error: Composer is not installed!', 'ping-my-slack' )
	);
}

/**
 * Run Plugin.
 *
 * @since 1.0.0
 *
 * @param string $autoload Composer Autoload file.
 * @return void
 */
function ping_my_slack_run( $autoload ): void {
	require_once $autoload;
	( \PingMySlack\Plugin::get_instance() )->run();
}
