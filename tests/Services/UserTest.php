<?php

namespace PingMySlack\Tests\Services;

use Mockery;
use WP_Mock\Tools\TestCase;
use PingMySlack\Core\Client;
use PingMySlack\Services\User;

/**
 * @covers \PingMySlack\Services\User::__construct
 * @covers \PingMySlack\Services\User::register
 * @covers \PingMySlack\Services\User::ping_on_user_creation
 * @covers \PingMySlack\Services\User::ping_on_user_modification
 * @covers \PingMySlack\Services\User::ping_on_user_deletion
 */
class UserTest extends TestCase {
	public function setUp(): void {
		\WP_Mock::setUp();

		$this->client = Mockery::mock( Client::class )->makePartial();
		$this->client->shouldAllowMockingProtectedMethods();

		$this->user = Mockery::mock( User::class )->makePartial();
		$this->user->shouldAllowMockingProtectedMethods();
		$this->user->client = $this->client;
	}

	public function tearDown(): void {
		\WP_Mock::tearDown();
	}
}
