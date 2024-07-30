<?php

namespace PingMySlack\Tests\Core;

use Mockery;
use WP_Mock\Tools\TestCase;
use PingMySlack\Core\Container;

use PingMySlack\Services\Post;
use PingMySlack\Services\User;
use PingMySlack\Services\Admin;
use PingMySlack\Services\Access;
use PingMySlack\Services\Themes;
use PingMySlack\Services\Comment;

/**
 * @covers \PingMySlack\Core\Container::__construct
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
		$this->assertTrue( in_array( Comment::class, Container::$services, true ) );
		$this->assertTrue( in_array( Post::class, Container::$services, true ) );
		$this->assertTrue( in_array( Themes::class, Container::$services, true ) );
		$this->assertTrue( in_array( User::class, Container::$services, true ) );
		$this->assertConditionsMet();
	}

	public function test_register() {
		\WP_Mock::userFunction( 'get_option' )
			->times( 6 )
			->with( 'ping_my_slack', [] )
			->andReturn(
				[
					'webhook'  => 'https://hooks.services.slack.com',
					'channel'  => '#general',
					'username' => 'Bryan',
				]
			);

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

		$this->container->register();

		$this->assertConditionsMet();
	}
}
