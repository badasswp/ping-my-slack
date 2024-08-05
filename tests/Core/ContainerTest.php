<?php

namespace PingMySlack\Tests\Core;

use Mockery;
use WP_Mock\Tools\TestCase;

use PingMySlack\Services\Boot;
use PingMySlack\Services\Post;
use PingMySlack\Services\User;
use PingMySlack\Core\Container;
use PingMySlack\Services\Admin;
use PingMySlack\Services\Theme;
use PingMySlack\Services\Access;
use PingMySlack\Services\Comment;
use PingMySlack\Abstracts\Service;

/**
 * @covers \PingMySlack\Core\Container::__construct
 * @covers \PingMySlack\Core\Container::register
 */
class ContainerTest extends TestCase {
	public function setUp(): void {
		\WP_Mock::setUp();

		$this->container = new Container();
	}

	public function tearDown(): void {
		\WP_Mock::tearDown();
	}

	public function test_container_has_list_of_services() {
		$this->assertTrue( in_array( Access::class, Container::$services, true ) );
		$this->assertTrue( in_array( Admin::class, Container::$services, true ) );
		$this->assertTrue( in_array( Boot::class, Container::$services, true ) );
		$this->assertTrue( in_array( Comment::class, Container::$services, true ) );
		$this->assertTrue( in_array( Post::class, Container::$services, true ) );
		$this->assertTrue( in_array( Theme::class, Container::$services, true ) );
		$this->assertTrue( in_array( User::class, Container::$services, true ) );
		$this->assertConditionsMet();
	}

	/*public function test_register() {
		$this->services = [
			'access'  => Access::get_instance(),
			'admin'   => Admin::get_instance(),
			'boot'    => Boot::get_instance(),
			'comment' => Comment::get_instance(),
			'post'    => Post::get_instance(),
			'theme'   => Theme::get_instance(),
			'user'    => User::get_instance(),
		];

		\WP_Mock::expectActionAdded( 'init', [ Service::$services['PingMySlack\Services\Boot'], 'ping_my_slack_translation' ] );
		\WP_Mock::expectActionAdded( 'wp_login', [ Service::$services['PingMySlack\Services\Access'], 'ping_on_user_login' ], 10, 2 );
		\WP_Mock::expectActionAdded( 'wp_logout', [ Service::$services['PingMySlack\Services\Access'], 'ping_on_user_logout' ] );
		\WP_Mock::expectActionAdded( 'plugins_loaded', [ Service::$services['PingMySlack\Services\Admin'], 'carbon_fields_init' ] );
		\WP_Mock::expectActionAdded( 'carbon_fields_register_fields', [ Service::$services['PingMySlack\Services\Admin'], 'get_admin_page' ] );
		\WP_Mock::expectActionAdded( 'transition_comment_status', [ Service::$services['PingMySlack\Services\Comment'], 'ping_on_comment_status_change' ], 10, 3 );
		\WP_Mock::expectActionAdded( 'transition_post_status', [ Service::$services['PingMySlack\Services\Post'], 'ping_on_post_status_change' ], 10, 3 );
		\WP_Mock::expectActionAdded( 'switch_theme', [ Service::$services['PingMySlack\Services\Theme'], 'ping_on_theme_change' ], 10, 3 );
		\WP_Mock::expectActionAdded( 'user_register', [ Service::$services['PingMySlack\Services\User'], 'ping_on_user_creation' ], 10, 2 );
		\WP_Mock::expectActionAdded( 'wp_update_user', [ Service::$services['PingMySlack\Services\User'], 'ping_on_user_modification' ], 10, 3 );
		\WP_Mock::expectActionAdded( 'deleted_user', [ Service::$services['PingMySlack\Services\User'], 'ping_on_user_deletion' ], 10, 3 );

		$this->container->register();

		$this->assertConditionsMet();
	}*/
}
