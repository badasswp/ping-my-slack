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

	public function test_register() {
		\WP_Mock::expectActionAdded( 'user_register', [ $this->user, 'ping_on_user_creation' ], 10, 2 );
		\WP_Mock::expectActionAdded( 'wp_update_user', [ $this->user, 'ping_on_user_modification' ], 10, 3 );
		\WP_Mock::expectActionAdded( 'deleted_user', [ $this->user, 'ping_on_user_deletion' ], 10, 3 );

		$this->user->register();

		$this->assertConditionsMet();
	}

	public function test_ping_on_user_creation() {
		$user_login = 'john@doe.com';

		$user             = Mockery::mock( \WP_User::class )->makePartial();
		$user->ID         = 1;
		$user->user_login = 'john@doe.com';

		\WP_Mock::expectFilter( 'ping_my_slack_user_creation_client', $this->user->client );

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

		\WP_Mock::userFunction( 'get_user_by' )
			->once()
			->with( 'id', 1 )
			->andReturn( $user );

		$message = "Ping: A User was just created! \nID: 1 \nUser: john@doe.com \nDate: 08:57:13, 01-07-2024";

		\WP_Mock::expectFilter(
			'ping_my_slack_user_creation_message',
			$message,
			$user->ID
		);

		$this->user->shouldReceive( 'get_date' )
			->once()
			->with()
			->andReturn( '08:57:13, 01-07-2024' );

		$this->user->client->shouldReceive( 'ping' )
			->once()
			->with( $message );

		$this->user->ping_on_user_creation( $user->ID, [] );

		$this->assertConditionsMet();
	}

	public function test_ping_on_user_modification() {
		$user_login = 'john@doe.com';

		$user             = Mockery::mock( \WP_User::class )->makePartial();
		$user->ID         = 1;
		$user->user_login = 'john@doe.com';

		\WP_Mock::expectFilter( 'ping_my_slack_user_modification_client', $this->user->client );

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

		\WP_Mock::userFunction( 'get_user_by' )
			->once()
			->with( 'id', 1 )
			->andReturn( $user );

		$message = "Ping: A User was just modified! \nID: 1 \nUser: john@doe.com \nDate: 08:57:13, 01-07-2024";

		\WP_Mock::expectFilter(
			'ping_my_slack_user_modification_message',
			$message,
			$user->ID
		);

		$this->user->shouldReceive( 'get_date' )
			->once()
			->with()
			->andReturn( '08:57:13, 01-07-2024' );

		$this->user->client->shouldReceive( 'ping' )
			->once()
			->with( $message );

		$this->user->ping_on_user_modification( $user->ID, [], [] );

		$this->assertConditionsMet();
	}
}
