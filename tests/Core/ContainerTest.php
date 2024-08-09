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
}
