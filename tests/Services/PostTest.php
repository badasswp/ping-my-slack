<?php

namespace PingMySlack\Tests\Services;

use Mockery;
use WP_Mock\Tools\TestCase;
use PingMySlack\Core\Client;
use PingMySlack\Services\Post;

/**
 * @covers \PingMySlack\Services\Post::__construct
 * @covers \PingMySlack\Services\Post::register
 * @covers \PingMySlack\Services\Post::ping_on_post_status_change
 */
class PostTest extends TestCase {
	public function setUp(): void {
		\WP_Mock::setUp();

		$this->client = Mockery::mock( Client::class )->makePartial();
		$this->client->shouldAllowMockingProtectedMethods();

		$this->post = Mockery::mock( Post::class )->makePartial();
		$this->post->shouldAllowMockingProtectedMethods();
		$this->post->client = $this->client;
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

	public function test_ping_on_post_status_change_bails_if_new_status_is_auto_draft() {
		$post = Mockery::mock( \WP_Post::class )->makePartial();
		$post->shouldAllowMockingProtectedMethods();

		$this->post->ping_on_post_status_change( 'auto-draft', 'draft', $post );

		$this->assertConditionsMet();
	}

	public function test_ping_on_post_status_change_passes() {
		$post = Mockery::mock( \WP_Post::class )->makePartial();
		$post->shouldAllowMockingProtectedMethods();

		$this->post->shouldReceive( 'get_message' )
			->once()
			->with( 'A Post was just published!' )
			->andReturn( 'A Post was just published!' );

		$this->client->shouldReceive( 'ping' )
			->once()
			->with( 'A Post was just published!' );

		\WP_Mock::expectFilter( 'ping_my_slack_post_client', $this->client );

		$this->post->ping_on_post_status_change( 'publish', 'draft', $post );

		$this->assertConditionsMet();
	}
}
