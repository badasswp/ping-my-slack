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
}
