<?php

namespace PingMySlack\Tests\Services;

use Mockery;
use WP_Mock\Tools\TestCase;
use PingMySlack\Core\Client;
use PingMySlack\Services\Access;

/**
 * @covers \PingMySlack\Services\Access::__construct
 * @covers \PingMySlack\Services\Access::register
 * @covers \PingMySlack\Services\Access::ping_on_user_login
 * @covers \PingMySlack\Services\Access::ping_on_user_logout
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

	public function test_ping_on_user_login() {
		$user_login = 'john@doe.com';

		$user     = Mockery::mock( \WP_User::class )->makePartial();
		$user->ID = 1;

		\WP_Mock::expectFilter( 'ping_my_slack_login_client', $this->access->client );

		\WP_Mock::userFunction(
			'esc_html__',
			[
				'times'  => 5,
				'return' => function ( $text, $domain = 'ping-my-slack' ) {
					return $text;
				},
			]
		);

		\WP_Mock::userFunction(
			'esc_html',
			[
				'times'  => 3,
				'return' => function ( $text ) {
					return $text;
				},
			]
		);

		$message = "Ping: A User just logged in! \nID: 1 \nUser: john@doe.com \nDate: 08:57:13, 01-07-2024";

		\WP_Mock::expectFilter(
			'ping_my_slack_login_message',
			$message,
			$user
		);

		$this->access->shouldReceive( 'get_date' )
			->once()
			->with()
			->andReturn( '08:57:13, 01-07-2024' );

		$this->access->client->shouldReceive( 'ping' )
			->once()
			->with( $message );

		$this->access->ping_on_user_login( $user_login, $user );

		$this->assertConditionsMet();
	}

	public function test_ping_on_user_logout() {
		$user_id = 1;

		$user             = Mockery::mock( \WP_User::class )->makePartial();
		$user->user_login = 'john@doe.com';

		\WP_Mock::userFunction( 'get_user_by' )
			->once()
			->with( 'id', 1 )
			->andReturn( $user );

		\WP_Mock::expectFilter( 'ping_my_slack_logout_client', $this->access->client );

		\WP_Mock::userFunction(
			'esc_html__',
			[
				'times'  => 5,
				'return' => function ( $text, $domain = 'ping-my-slack' ) {
					return $text;
				},
			]
		);

		\WP_Mock::userFunction(
			'esc_html',
			[
				'times'  => 3,
				'return' => function ( $text ) {
					return $text;
				},
			]
		);

		$message = "Ping: A User just logged out! \nID: 1 \nUser: john@doe.com \nDate: 08:57:13, 01-07-2024";

		\WP_Mock::expectFilter(
			'ping_my_slack_logout_message',
			$message,
			$user
		);

		$this->access->shouldReceive( 'get_date' )
			->once()
			->with()
			->andReturn( '08:57:13, 01-07-2024' );

		$this->access->client->shouldReceive( 'ping' )
			->once()
			->with( $message );

		$this->access->ping_on_user_logout( $user_id );

		$this->assertConditionsMet();
	}
}