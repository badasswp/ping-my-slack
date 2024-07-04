<?php
/**
 * Plugin Name: Ping My Slack
 * Plugin URI:  https://github.com/badasswp/ping-my-slack
 * Description: Get notifications on Slack when changes are made on your WP website.
 * Version:     1.0.0
 * Author:      badasswp
 * Author URI:  https://github.com/badasswp
 * License:     GPL v2 or later
 * License URI: https://www.gnu.org/licenses/gpl-3.0.html
 * Text Domain: ping-my-slack
 * Domain Path: /languages
 *
 * @package PingMySlack
 */

namespace badasswp\PingMySlack;

if ( ! defined( 'ABSPATH' ) ) {
	wp_die();
}

require_once __DIR__ . './init.php';

// Bail out, if Composer is NOT installed.
if ( ! file_exists( PINGMYSLACK ) ) {
	add_action( 'admin_notices', 'ping_my_slack_notice' );
	return;
}

// Run Plugin.
ping_my_slack_run( PINGMYSLACK );
