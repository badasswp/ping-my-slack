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
		$this->assertTrue( in_array( Access::class, Container::$services, TRUE ) );
		$this->assertTrue( in_array( Admin::class, Container::$services, TRUE ) );
		$this->assertTrue( in_array( Comment::class, Container::$services, TRUE ) );
		$this->assertTrue( in_array( Post::class, Container::$services, TRUE ) );
		$this->assertTrue( in_array( Themes::class, Container::$services, TRUE ) );
		$this->assertTrue( in_array( User::class, Container::$services, TRUE ) );
		$this->assertConditionsMet();
	}
}
