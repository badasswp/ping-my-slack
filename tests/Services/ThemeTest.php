<?php

namespace PingMySlack\Tests\Services;

use Mockery;
use WP_Mock\Tools\TestCase;
use PingMySlack\Core\Client;
use PingMySlack\Services\Theme;

/**
 * @covers \PingMySlack\Services\Theme::__construct
 * @covers \PingMySlack\Services\Theme::register
 * @covers \PingMySlack\Services\Theme::ping_on_theme_change
 * @covers \PingMySlack\Services\Theme::get_message
 */
class ThemeTest extends TestCase {
	public function setUp(): void {
		\WP_Mock::setUp();

		$this->client = Mockery::mock( Client::class )->makePartial();
		$this->client->shouldAllowMockingProtectedMethods();

		$this->theme = Mockery::mock( Theme::class )->makePartial();
		$this->theme->shouldAllowMockingProtectedMethods();
		$this->theme->client = $this->client;
	}

	public function tearDown(): void {
		\WP_Mock::tearDown();
	}

	public function test_register() {
		\WP_Mock::expectActionAdded( 'switch_theme', [ $this->theme, 'ping_on_theme_change' ], 10, 3 );

		$this->theme->register();

		$this->assertConditionsMet();
	}

	public function test_ping_on_theme_change_bails_if_theme_is_unchanged() {
		$theme = Mockery::mock( \WP_Theme::class )->makePartial();
		$theme->shouldAllowMockingProtectedMethods();

		$this->theme->ping_on_theme_change( 'Elementor', $theme, $theme );

		$this->assertConditionsMet();
	}
}
