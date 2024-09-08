<?php

namespace PingMySlack\Tests;

use Mockery;
use WP_Mock\Tools\TestCase;

use PingMySlack\Plugin;
use PingMySlack\Abstracts\Service;

use PingMySlack\Services\Boot;
use PingMySlack\Services\Post;
use PingMySlack\Services\User;
use PingMySlack\Services\Admin;
use PingMySlack\Services\Theme;
use PingMySlack\Services\Access;
use PingMySlack\Services\Comment;

/**
 * @covers \PingMySlack\Plugin::__construct
 * @covers \PingMySlack\Plugin::run
 */
class PluginTest extends TestCase {
	public function setUp(): void {
		\WP_Mock::setUp();
	}

	public function tearDown(): void {
		\WP_Mock::tearDown();
	}

	public function test_plugin_returns_same_instance() {
		$instance1 = Plugin::get_instance();
		$instance2 = Plugin::get_instance();

		$this->assertSame( $instance1, $instance2 );
		$this->assertConditionsMet();
	}

	public function test_plugin_runs_singleton_instance() {
		\WP_Mock::userFunction( 'get_option' )
			->times( 7 )
			->with( 'ping_me_on_slack', [] )
			->andReturn(
				[
					'webhook'  => 'https://hooks.services.slack.com',
					'channel'  => '#general',
					'username' => 'Bryan',
				]
			);

		$this->services = [
			'Access'  => Access::get_instance(),
			'Admin'   => Admin::get_instance(),
			'Boot'    => Boot::get_instance(),
			'Comment' => Comment::get_instance(),
			'Post'    => Post::get_instance(),
			'Theme'   => Theme::get_instance(),
			'User'    => User::get_instance(),
		];

		\WP_Mock::expectActionAdded(
			'init',
			[
				Service::$services['PingMySlack\Services\Boot'],
				'ping_me_on_slack_translation',
			]
		);

		\WP_Mock::expectActionAdded(
			'wp_login',
			[
				Service::$services['PingMySlack\Services\Access'],
				'ping_on_user_login',
			],
			10,
			2
		);

		\WP_Mock::expectActionAdded(
			'wp_logout',
			[
				Service::$services['PingMySlack\Services\Access'],
				'ping_on_user_logout',
			]
		);

		\WP_Mock::expectActionAdded(
			'plugins_loaded',
			[
				Service::$services['PingMySlack\Services\Admin'],
				'carbon_fields_init',
			]
		);

		\WP_Mock::expectActionAdded(
			'carbon_fields_register_fields',
			[
				Service::$services['PingMySlack\Services\Admin'],
				'get_admin_page',
			]
		);

		\WP_Mock::expectActionAdded(
			'transition_comment_status',
			[
				Service::$services['PingMySlack\Services\Comment'],
				'ping_on_comment_status_change',
			],
			10,
			3
		);

		\WP_Mock::expectActionAdded(
			'transition_post_status',
			[
				Service::$services['PingMySlack\Services\Post'],
				'ping_on_post_status_change',
			],
			10,
			3
		);

		\WP_Mock::expectActionAdded(
			'switch_theme',
			[
				Service::$services['PingMySlack\Services\Theme'],
				'ping_on_theme_change',
			],
			10,
			3
		);

		\WP_Mock::expectActionAdded(
			'user_register',
			[
				Service::$services['PingMySlack\Services\User'],
				'ping_on_user_creation',
			],
			10,
			2
		);

		\WP_Mock::expectActionAdded(
			'wp_update_user',
			[
				Service::$services['PingMySlack\Services\User'],
				'ping_on_user_modification',
			],
			10,
			3
		);

		\WP_Mock::expectActionAdded(
			'deleted_user',
			[
				Service::$services['PingMySlack\Services\User'],
				'ping_on_user_deletion',
			],
			10,
			3
		);

		$instance = Plugin::get_instance();
		$instance->run();

		$this->assertConditionsMet();
	}
}
