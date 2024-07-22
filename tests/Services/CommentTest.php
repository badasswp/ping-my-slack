<?php

namespace PingMySlack\Tests\Services;

use Mockery;
use WP_Mock\Tools\TestCase;
use PingMySlack\Core\Client;
use PingMySlack\Services\Comment;

/**
 * @covers \PingMySlack\Services\Comment::__construct
 * @covers \PingMySlack\Services\Comment::register
 * @covers \PingMySlack\Services\Comment::ping_on_comment_status_change
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

	public function test_ping_on_comment_status_change_bails() {
		$comment = Mockery::mock( \WP_Comment::class )->makePartial();
		$comment->shouldAllowMockingProtectedMethods();

		$this->comment->ping_on_comment_status_change( 'approved', 'approved', $comment );

		$this->assertConditionsMet();
	}

	public function test_ping_on_comment_status_change_passes() {
		$comment = Mockery::mock( \WP_Comment::class )->makePartial();
		$comment->shouldAllowMockingProtectedMethods();

		$this->comment->shouldReceive( 'get_message' )
			->once()
			->with( 'A Comment was just trashed!' )
			->andReturn( 'A Comment was just trashed!' );

		$this->client->shouldReceive( 'ping' )
			->once()
			->with( 'A Comment was just trashed!' );

		\WP_Mock::expectFilter( 'ping_my_slack_comment_client', $this->client );

		$this->comment->ping_on_comment_status_change( 'trash', 'approved', $comment );

		$this->assertConditionsMet();
	}
}
