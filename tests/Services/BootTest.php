<?php

namespace PingMySlack\Tests\Services;

use Mockery;
use WP_Mock\Tools\TestCase;
use PingMySlack\Core\Client;
use PingMySlack\Services\Boot;

/**
 * @covers \PingMySlack\Services\Boot::__construct
 * @covers \PingMySlack\Services\Boot::register
 */
class BootTest extends TestCase {
	public function setUp(): void {
		\WP_Mock::setUp();

		$this->client = Mockery::mock( Client::class )->makePartial();
		$this->client->shouldAllowMockingProtectedMethods();

		$this->boot = Mockery::mock( Boot::class )->makePartial();
		$this->boot->shouldAllowMockingProtectedMethods();
		$this->boot->client = $this->client;
	}

	public function tearDown(): void {
		\WP_Mock::tearDown();
	}

	public function test_register() {
		\WP_Mock::expectActionAdded( 'init', [ $this->boot, 'ping_my_slack_translation' ] );

		$this->boot->register();

		$this->assertConditionsMet();
	}
}
