<?php

namespace PingMySlack\Tests\Core;

use Mockery;
use WP_Mock\Tools\TestCase;
use PingMySlack\Core\Client;
use Maknz\Slack\Client as SlackClient;

/**
 * @covers \PingMySlack\Core\Client::__construct
 */
class ClientTest extends TestCase {
	public function setUp(): void {
		\WP_Mock::setUp();

		\WP_Mock::userFunction( 'get_option' )
			->once()
			->with( 'ping_me_on_slack', [] )
			->andReturn(
				[
					'channel'  => '#general',
					'username' => 'Bryan',
					'webhook'  => 'https://slack.com/services',
				]
			);

		$this->client = new Client();
	}

	public function tearDown(): void {
		\WP_Mock::tearDown();
	}

	public function test_args_set_up_default() {
		$this->assertSame(
			'#general',
			$this->client->args['channel']
		);
		$this->assertSame(
			'Bryan',
			$this->client->args['username']
		);
		$this->assertConditionsMet();
	}
}
