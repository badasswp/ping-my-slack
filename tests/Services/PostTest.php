<?php

namespace PingMySlack\Tests\Services;

use Mockery;
use WP_Mock\Tools\TestCase;
use PingMySlack\Services\Post;

/**
 * @covers \PingMySlack\Services\Post::__construct
 * @covers \PingMySlack\Services\Post::register
 */
class PostTest extends TestCase {
	public function setUp(): void {
		\WP_Mock::setUp();

		$this->post = Mockery::mock( Post::class )->makePartial();
		$this->post->shouldAllowMockingProtectedMethods();
	}

	public function tearDown(): void {
		\WP_Mock::tearDown();
	}

	public function test_register() {
		\WP_Mock::expectActionAdded( 'transition_post_status', [ $this->post, 'ping_on_post_status_change' ], 10, 3 );

		$this->post->register();

		$this->assertConditionsMet();
	}

	public function test_ping_on_post_status_change_bails_if_status_is_unchanged() {
		$post = Mockery::mock( \WP_Post::class )->makePartial();
		$post->shouldAllowMockingProtectedMethods();

		$this->post->ping_on_post_status_change( 'draft', 'draft', $post );

		$this->assertConditionsMet();
	}
}
