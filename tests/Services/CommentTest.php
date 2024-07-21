<?php

namespace PingMySlack\Tests\Services;

use Mockery;
use WP_Mock\Tools\TestCase;
use PingMySlack\Core\Client;
use PingMySlack\Services\Comment;

/**
 * @covers \PingMySlack\Services\Comment::__construct
 * @covers \PingMySlack\Services\Comment::register
 */
class CommentTest extends TestCase {
	public function setUp(): void {
		\WP_Mock::setUp();

		$this->client = Mockery::mock( Client::class )->makePartial();
		$this->client->shouldAllowMockingProtectedMethods();

		$this->comment = Mockery::mock( Comment::class )->makePartial();
		$this->comment->shouldAllowMockingProtectedMethods();
		$this->comment->client = $this->client;
	}

	public function tearDown(): void {
		\WP_Mock::tearDown();
	}

	public function test_register() {
		\WP_Mock::expectActionAdded( 'transition_comment_status', [ $this->comment, 'ping_on_comment_status_change' ], 10, 3 );

		$this->comment->register();

		$this->assertConditionsMet();
	}
}
