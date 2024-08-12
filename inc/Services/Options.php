<?php
/**
 * Options Class.
 *
 * This class holds the logic for registering
 * the plugin's admin page.
 *
 * @package PingMySlack
 */

namespace PingMySlack\Services;

use PingMySlack\Abstracts\Service;
use PingMySlack\Interfaces\Kernel;

class Options extends Service implements Kernel {
	/**
	 * Bind to WP.
	 *
	 * @since 1.1.0
	 *
	 * @return void
	 */
	public function register(): void {
		add_action( 'admin_init', [ $this, 'register_options_init' ] );
		add_action( 'admin_menu', [ $this, 'register_options_menu' ] );
		add_action( 'admin_enqueue_scripts', [ $this, 'register_options_styles' ] );
	}

	/**
	 * Register Options Menu.
	 *
	 * This controls the menu display for the plugin.
	 *
	 * @since 1.0.0
	 *
	 * @return void
	 */
	public function register_options_menu(): void {
		add_menu_page(
			__( 'Lorem Ipsum Dolor', 'lorem-ipsum-dolor' ),
			__( 'Lorem Ipsum Dolor', 'lorem-ipsum-dolor' ),
			'manage_options',
			'lorem-ipsum-dolor',
			[ $this, 'register_options_page' ],
			'dashicons-chart-pie',
			7
		);
	}

	/**
	 * Register Options Page.
	 *
	 * This controls the display of the menu page.
	 *
	 * @since 1.0.0
	 *
	 * @return void
	 */
	public function register_options_page(): void {
		vprintf(
			'<section class="wrap">
				<h1>%s</h1>
				<p>%s</p>
			</section>',
			[
				'caption' => esc_html__( 'Lorem Ipsum Dolor', 'lorem-ipsum-dolor' ),
				'summary' => esc_html__( 'Convert your WordPress JPG/PNG images to WebP formats during runtime.', 'image-converter-webp' )
			]
		);
	}

	/**
	 * Register Settings.
	 *
	 * This method handles all save actions for the fields
	 * on the Plugin's settings page.
	 *
	 * @since 1.0.0
	 *
	 * @return void
	 */
	public function register_options_init(): void {
		if ( ! isset( $_POST['options_save'] ) || ! isset( $_POST['options_nonce'] ) ) {
			return;
		}
	}

	/**
	 * Register Styles.
	 *
	 * @since 1.1.0
	 *
	 * @return void
	 */
	public function register_options_styles(): void {
		wp_enqueue_style(
			'lorem-ipsum-dolor',
			plugins_url( 'image-converter-webp/inc/Views/css/styles.css' ),
			[],
			true,
			'all'
		);
	}
}
