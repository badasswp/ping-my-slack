<?php

namespace PingMySlack\Tests\Services;

use Mockery;
use WP_Mock\Tools\TestCase;
use PingMySlack\Core\Client;
use PingMySlack\Services\Access;

/**
 * @covers \PingMySlack\Services\Access::__construct
 * @covers \PingMySlack\Services\Access::register
 */
class AccessTest extends TestCase {
	public function setUp(): void {
		\WP_Mock::setUp();

		$this->client = Mockery::mock( Client::class )->makePartial();
		$this->client->shouldAllowMockingProtectedMethods();

		$this->access = Mockery::mock( Access::class )->makePartial();
		$this->access->shouldAllowMockingProtectedMethods();
		$this->access->client = $this->client;
	}

	public function tearDown(): void {
		\WP_Mock::tearDown();
	}

	public function test_register() {
		\WP_Mock::expectActionAdded( 'wp_login', [ $this->access, 'ping_on_user_login' ], 10, 2 );
		\WP_Mock::expectActionAdded( 'wp_logout', [ $this->access, 'ping_on_user_logout' ] );

		$this->access->register();

		$this->assertConditionsMet();
	}
}
