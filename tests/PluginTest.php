<?php

namespace PingMySlack\Tests;

use Mockery;
use WP_Mock;
use PingMySlack\Plugin;
use WP_Mock\Tools\TestCase;

use PingMySlack\Services\Post;
use PingMySlack\Services\User;
use PingMySlack\Services\Admin;
use PingMySlack\Services\Access;
use PingMySlack\Services\Themes;
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
		$this->services = [
			'access'  => Access::get_instance(),
			'admin'   => Admin::get_instance(),
			'comment' => Comment::get_instance(),
			'post'    => Post::get_instance(),
			'theme'   => Themes::get_instance(),
			'user'    => User::get_instance(),
		];

		\WP_Mock::expectActionAdded( 'wp_login', [ $this->services['access'], 'ping_on_user_login' ], 10, 2 );
		\WP_Mock::expectActionAdded( 'wp_logout', [ $this->services['access'], 'ping_on_user_logout' ] );
		\WP_Mock::expectActionAdded( 'plugins_loaded', [ $this->services['admin'], 'carbon_fields_init' ] );
		\WP_Mock::expectActionAdded( 'carbon_fields_register_fields', [ $this->services['admin'], 'get_admin_page' ] );
		\WP_Mock::expectActionAdded( 'transition_comment_status', [ $this->services['comment'], 'ping_on_comment_status_change' ], 10, 3 );
		\WP_Mock::expectActionAdded( 'transition_post_status', [ $this->services['post'], 'ping_on_post_status_change' ], 10, 3 );
		\WP_Mock::expectActionAdded( 'switch_theme', [ $this->services['theme'], 'ping_on_theme_change' ], 10, 3 );
		\WP_Mock::expectActionAdded( 'user_register', [ $this->services['user'], 'ping_on_user_creation' ], 10, 2 );
		\WP_Mock::expectActionAdded( 'wp_update_user', [ $this->services['user'], 'ping_on_user_modification' ], 10, 3 );
		\WP_Mock::expectActionAdded( 'deleted_user', [ $this->services['user'], 'ping_on_user_deletion' ], 10, 3 );

		$instance = Plugin::get_instance();
		$instance->run();

		$this->assertConditionsMet();
	}
}
