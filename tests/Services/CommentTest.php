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
	}

	public function tearDown(): void {
		\WP_Mock::tearDown();
	}

	public function test_register() {
		$client = Mockery::mock( Client::class )->makePartial();
		$client->shouldAllowMockingProtectedMethods();

		$comment = Mockery::mock( Comment::class )->makePartial();
		$comment->shouldAllowMockingProtectedMethods();
		$comment->client = $client;

		\WP_Mock::expectActionAdded( 'transition_comment_status', [ $comment, 'ping_on_comment_status_change' ], 10, 3 );

		$comment->register();

		$this->assertConditionsMet();
	}
}
