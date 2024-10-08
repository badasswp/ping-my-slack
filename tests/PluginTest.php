<?php

namespace PingMeOnSlack\Tests;

use Mockery;
use WP_Mock\Tools\TestCase;

use PingMeOnSlack\Plugin;
use PingMeOnSlack\Abstracts\Service;

use PingMeOnSlack\Services\Boot;
use PingMeOnSlack\Services\Post;
use PingMeOnSlack\Services\User;
use PingMeOnSlack\Services\Admin;
use PingMeOnSlack\Services\Theme;
use PingMeOnSlack\Services\Access;
use PingMeOnSlack\Services\Comment;

/**
 * @covers \PingMeOnSlack\Plugin::__construct
 * @covers \PingMeOnSlack\Plugin::run
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
				Service::$services['PingMeOnSlack\Services\Boot'],
				'ping_me_on_slack_translation',
			]
		);

		\WP_Mock::expectActionAdded(
			'wp_login',
			[
				Service::$services['PingMeOnSlack\Services\Access'],
				'ping_on_user_login',
			],
			10,
			2
		);

		\WP_Mock::expectActionAdded(
			'wp_logout',
			[
				Service::$services['PingMeOnSlack\Services\Access'],
				'ping_on_user_logout',
			]
		);

		\WP_Mock::expectActionAdded(
			'plugins_loaded',
			[
				Service::$services['PingMeOnSlack\Services\Admin'],
				'carbon_fields_init',
			]
		);

		\WP_Mock::expectActionAdded(
			'carbon_fields_register_fields',
			[
				Service::$services['PingMeOnSlack\Services\Admin'],
				'get_admin_page',
			]
		);

		\WP_Mock::expectActionAdded(
			'transition_comment_status',
			[
				Service::$services['PingMeOnSlack\Services\Comment'],
				'ping_on_comment_status_change',
			],
			10,
			3
		);

		\WP_Mock::expectActionAdded(
			'transition_post_status',
			[
				Service::$services['PingMeOnSlack\Services\Post'],
				'ping_on_post_status_change',
			],
			10,
			3
		);

		\WP_Mock::expectActionAdded(
			'switch_theme',
			[
				Service::$services['PingMeOnSlack\Services\Theme'],
				'ping_on_theme_change',
			],
			10,
			3
		);

		\WP_Mock::expectActionAdded(
			'user_register',
			[
				Service::$services['PingMeOnSlack\Services\User'],
				'ping_on_user_creation',
			],
			10,
			2
		);

		\WP_Mock::expectActionAdded(
			'wp_update_user',
			[
				Service::$services['PingMeOnSlack\Services\User'],
				'ping_on_user_modification',
			],
			10,
			3
		);

		\WP_Mock::expectActionAdded(
			'deleted_user',
			[
				Service::$services['PingMeOnSlack\Services\User'],
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
